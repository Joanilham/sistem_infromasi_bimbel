<?php

namespace App\Http\Controllers\Keuangan;
use App\Models\MasterData\Kantor;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\KategoriPemasukan;
use App\Models\Keuangan\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Keuangan\PemasukanRequest;
use App\Http\Requests\Keuangan\KategoriPemasukanRequest;

class PemasukanController extends Controller
{
    public function __construct()
    {
        $this->middleware('ensure_role:super admin')->only(['edit', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $search    = $request->input('search', '');
        $perPage   = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $kategoriId = $request->input('kategori_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $sort      = in_array($request->input('sort'), ['id', 'tanggal', 'nominal', 'keterangan']) ? $request->input('sort') : 'tanggal';
        $order     = $request->input('order', 'desc') === 'asc' ? 'asc' : 'desc';

        $user = Auth::user();
        $isSuperAdmin = strtolower($user->level) === 'super admin';

        if ($isSuperAdmin) {
            // Super Admin can filter by request parameters, fallback to session if not specified in request
            $kantorId = $request->has('kantor_id') ? $request->input('kantor_id') : session('kantor_id');
        } else {
            // Branch Admin is forced to their own branch
            $kantorId = session('kantor_id');
        }

        if ($kantorId === 'all' || empty($kantorId)) {
            $kantorId = null;
        }

        $query = $this->buildIndexQuery($kantorId, $search, $kategoriId, $startDate, $endDate);

        // Total Cabang Ini (filtered by current active branch)
        $totalCabangIni = $this->calculateTotalCabangIni($kantorId, $startDate, $endDate);

        // Total Seluruh Cabang (nationwide aggregate)
        $totalSeluruhCabang = $this->calculateTotalSeluruhCabang($startDate, $endDate);

        $pemasukan = $query->orderBy($sort, $order)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $kategoris = KategoriPemasukan::orderBy('nama')->get();
        $kantors = $isSuperAdmin ? \App\Models\MasterData\Kantor::orderBy('nama_kantor')->get() : collect();

        $selectedKantorName = 'Semua Cabang';
        if ($kantorId) {
            $selectedKantorObj = \App\Models\MasterData\Kantor::find($kantorId);
            $selectedKantorName = $selectedKantorObj ? $selectedKantorObj->nama_kantor : 'Semua Cabang';
        } elseif (!$isSuperAdmin && $user->kantor) {
            $selectedKantorName = $user->kantor->nama_kantor;
        }

        return view('keuangan.pemasukan.index', compact(
            'pemasukan', 'kategoris', 'kantors', 'kantorId', 'isSuperAdmin',
            'search', 'perPage', 'kategoriId', 'startDate', 'endDate', 'sort', 'order',
            'totalCabangIni', 'totalSeluruhCabang', 'selectedKantorName'
        ));
    }

    public function store(PemasukanRequest $request)
    {
        $validated = $request->validated();
        $kantorId = session('kantor_id') === 'all' ? null : session('kantor_id');

        Pemasukan::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'kantor_id' => $kantorId
        ]));
        return back()->with('success', 'Pemasukan berhasil disimpan.');
    }

    public function edit(Pemasukan $pemasukan)
    {
        $kategoris = KategoriPemasukan::orderBy('nama')->get();
        return view('keuangan.pemasukan.edit', compact('pemasukan', 'kategoris'));
    }

    public function update(PemasukanRequest $request, Pemasukan $pemasukan)
    {
        $validated = $request->validated();

        $pemasukan->update($validated);
        return redirect()->route('keuangan.pemasukan.index')->with('success', 'Pemasukan berhasil diperbarui.');
    }

    public function destroy(Pemasukan $pemasukan)
    {
        $pemasukan->delete();
        return back()->with('success', 'Pemasukan dihapus.');
    }

    // ── Kategori ────────────────────────────────────────────────
    public function indexKategori(Request $request)
    {
        $sort  = in_array($request->input('sort'), ['id', 'nama']) ? $request->input('sort') : 'nama';
        $order = $request->input('order', 'asc') === 'desc' ? 'desc' : 'asc';
        $kategoris = KategoriPemasukan::withCount('pemasukan')->orderBy($sort, $order)->get();
        return view('keuangan.pemasukan.kategori', compact('kategoris'));
    }

    public function storeKategori(KategoriPemasukanRequest $request)
    {
        $validated = $request->validated();
        KategoriPemasukan::create($validated);
        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function destroyKategori(KategoriPemasukan $kategoriPemasukan)
    {
        if ($kategoriPemasukan->pemasukan()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki data pemasukan.');
        }
        $kategoriPemasukan->delete();
        return back()->with('success', 'Kategori dihapus.');
    }

    // ─── PRIVATE HELPERS ──────────────────────────────────────────────

    private function buildIndexQuery($kantorId, $search, $kategoriId, $startDate, $endDate)
    {
        return Pemasukan::with('kategori')
            ->when($kantorId, fn($q) => $q->where('kantor_id', $kantorId))
            ->when($search, fn($q) =>
                $q->where(function($sub) use ($search) {
                    $sub->where('keterangan', 'like', "%{$search}%")
                       ->orWhereHas('kategori', fn($q2) => $q2->where('nama', 'like', "%{$search}%"));
                })
            )
            ->when($kategoriId, fn($q) => $q->where('kategori_id', $kategoriId))
            ->when($startDate, fn($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal', '<=', $endDate));
    }

    private function calculateTotalCabangIni($kantorId, $startDate, $endDate)
    {
        return Pemasukan::when($kantorId, fn($q) => $q->where('kantor_id', $kantorId))
            ->when($startDate, fn($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal', '<=', $endDate))
            ->sum('nominal');
    }

    private function calculateTotalSeluruhCabang($startDate, $endDate)
    {
        return Pemasukan::when($startDate, fn($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal', '<=', $endDate))
            ->sum('nominal');
    }
}


