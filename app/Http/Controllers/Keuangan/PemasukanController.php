<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\KategoriPemasukan;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemasukanController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search', '');
        $perPage   = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $kategoriId = $request->input('kategori_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $sort      = in_array($request->input('sort'), ['id', 'tanggal', 'nominal', 'keterangan']) ? $request->input('sort') : 'tanggal';
        $order     = $request->input('order', 'desc') === 'asc' ? 'asc' : 'desc';

        $pemasukan = Pemasukan::with('kategori')
            ->when($search, fn($q) =>
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('kategori', fn($q2) => $q2->where('nama', 'like', "%{$search}%"))
            )
            ->when($kategoriId, fn($q) => $q->where('kategori_id', $kategoriId))
            ->when($startDate, fn($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tanggal', '<=', $endDate))
            ->orderBy($sort, $order)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $kategoris = KategoriPemasukan::orderBy('nama')->get();

        return view('keuangan.pemasukan.index', compact('pemasukan', 'kategoris', 'search', 'perPage', 'kategoriId', 'startDate', 'endDate', 'sort', 'order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'kategori_id' => 'required|exists:kategori_pemasukan,id',
            'nominal'     => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        Pemasukan::create(array_merge($validated, ['user_id' => Auth::id()]));
        return back()->with('success', 'Pemasukan berhasil disimpan.');
    }

    public function update(Request $request, Pemasukan $pemasukan)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'kategori_id' => 'required|exists:kategori_pemasukan,id',
            'nominal'     => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $pemasukan->update($validated);
        return back()->with('success', 'Pemasukan berhasil diperbarui.');
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

    public function storeKategori(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100|unique:kategori_pemasukan,nama']);
        KategoriPemasukan::create($request->only('nama'));
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
}
