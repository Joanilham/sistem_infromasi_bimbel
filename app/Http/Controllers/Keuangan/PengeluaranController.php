<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengeluaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
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

        $query = Pengeluaran::with('kategori')
            ->when($kantorId, fn($q) => $q->whereHas('user', fn($qu) => $qu->where('kantor_id', $kantorId)))
            ->when($search, fn($q) =>
                $q->where(function($sub) use ($search) {
                    $sub->where('keterangan', 'like', "%{$search}%")
                       ->orWhereHas('kategori', fn($q2) => $q2->where('nama', 'like', "%{$search}%"));
                })
            )
            ->when($kategoriId, fn($q) => $q->where('kategori_id', $kategoriId))
            ->when($startDate, fn($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal', '<=', $endDate));

        // Total Cabang Ini (filtered by current active branch)
        $totalCabangIni = Pengeluaran::when($kantorId, fn($q) => $q->whereHas('user', fn($qu) => $qu->where('kantor_id', $kantorId)))
            ->when($startDate, fn($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal', '<=', $endDate))
            ->sum('nominal');

        // Total Seluruh Cabang (nationwide aggregate)
        $totalSeluruhCabang = Pengeluaran::when($startDate, fn($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal', '<=', $endDate))
            ->sum('nominal');

        $pengeluaran = $query->orderBy($sort, $order)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $kategoris = KategoriPengeluaran::orderBy('nama')->get();
        $kantors = $isSuperAdmin ? \App\Models\Kantor::orderBy('nama_kantor')->get() : collect();

        $selectedKantorName = 'Semua Cabang';
        if ($kantorId) {
            $selectedKantorObj = \App\Models\Kantor::find($kantorId);
            $selectedKantorName = $selectedKantorObj ? $selectedKantorObj->nama_kantor : 'Semua Cabang';
        } elseif (!$isSuperAdmin && $user->kantor) {
            $selectedKantorName = $user->kantor->nama_kantor;
        }

        return view('keuangan.pengeluaran.index', compact(
            'pengeluaran', 'kategoris', 'kantors', 'kantorId', 'isSuperAdmin',
            'search', 'perPage', 'kategoriId', 'startDate', 'endDate', 'sort', 'order',
            'totalCabangIni', 'totalSeluruhCabang', 'selectedKantorName'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'kategori_id' => 'required|exists:kategori_pengeluaran,id',
            'nominal'     => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        Pengeluaran::create(array_merge($validated, ['user_id' => Auth::id()]));
        return back()->with('success', 'Pengeluaran berhasil disimpan.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $kategoris = KategoriPengeluaran::orderBy('nama')->get();
        return view('keuangan.pengeluaran.edit', compact('pengeluaran', 'kategoris'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'kategori_id' => 'required|exists:kategori_pengeluaran,id',
            'nominal'     => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $pengeluaran->update($validated);
        return redirect()->route('keuangan.pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return back()->with('success', 'Pengeluaran dihapus.');
    }

    // ── Kategori ────────────────────────────────────────────────
    public function indexKategori(Request $request)
    {
        $sort  = in_array($request->input('sort'), ['id', 'nama']) ? $request->input('sort') : 'nama';
        $order = $request->input('order', 'asc') === 'desc' ? 'desc' : 'asc';
        $kategoris = KategoriPengeluaran::withCount('pengeluaran')->orderBy($sort, $order)->get();
        return view('keuangan.pengeluaran.kategori', compact('kategoris'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100|unique:kategori_pengeluaran,nama']);
        KategoriPengeluaran::create($request->only('nama'));
        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function destroyKategori(KategoriPengeluaran $kategoriPengeluaran)
    {
        if ($kategoriPengeluaran->pengeluaran()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki data pengeluaran.');
        }
        $kategoriPengeluaran->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
