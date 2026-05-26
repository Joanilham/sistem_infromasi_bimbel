<?php

namespace App\Http\Controllers;

use App\Models\PaketBimbingan;
use Illuminate\Http\Request;

class PaketBimbinganController extends Controller
{
    use \App\Traits\HandlesImageUpload;

    public function index(Request $request)
    {
        $search     = $request->input('search', '');
        $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort       = in_array($request->input('sort'), ['id', 'nama_paket', 'urutan']) ? $request->input('sort') : 'urutan';
        $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'asc';

        // 1. HAPUS inContext() agar Admin bisa melihat semua paket secara global
        $paketBimbingans = PaketBimbingan::query()
            ->when($search, fn($q) =>
                $q->where('nama_paket', 'like', "%{$search}%")
            )
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.paket_bimbingan.index', compact('paketBimbingans'));
    }

    public function create()
    {
        // 2. PROTEKSI AKSES: Hanya Super Admin / Admin Pusat yang boleh akses halaman tambah
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak. Hanya Pusat yang berhak menambah Paket Bimbingan.');
        }

        return view('admin.paket_bimbingan.create');
    }

    public function edit(PaketBimbingan $paketBimbingan)
    {
        // PROTEKSI AKSES EDIT
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak. Hanya Pusat yang berhak mengedit Paket Bimbingan.');
        }

        return view('admin.paket_bimbingan.edit', compact('paketBimbingan'));
    }

    public function store(Request $request)
    {
        // PROTEKSI AKSES STORE
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak.');
        }

        // Bersihkan titik sebelum validasi
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
            'harga_coret' => str_replace('.', '', $request->harga_coret),
        ]);

        $validated = $request->validate([
            'nama_paket'        => 'required|string|max:255',
            'nominal'           => 'required|numeric|min:0',
            'dp_persen_minimal' => 'required|integer|min:1|max:100', // <-- TAMBAHAN: Validasi DP Minimal
            'bisa_dicicil'      => 'required|boolean',
            'max_cicilan'       => 'required_if:bisa_dicicil,1|integer|min:1',
            'harga_coret'       => 'nullable|numeric|min:0',
            'durasi_jumlah'     => 'nullable|integer|min:1',
            'durasi_satuan'     => 'nullable|string|in:Bulan,Tahun',
            'deskripsi'         => 'nullable|string',
            'benefits'          => 'nullable|string',
            'target_peserta'    => 'nullable|string',
            'fasilitas'         => 'nullable|string',
            'is_featured'       => 'nullable|boolean',
            'label_populer'     => 'nullable|string|max:50',
            'urutan'            => 'nullable|integer',
            'gambar_paket'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'nama_paket.required'        => 'Nama paket wajib diisi.',
            'nominal.required'           => 'Harga promo wajib diisi.',
            'nominal.numeric'            => 'Harga harus berupa angka.',
            'dp_persen_minimal.required' => 'Persentase DP minimal wajib diisi.',
            'dp_persen_minimal.integer'  => 'DP minimal harus berupa angka bulat.',
            'dp_persen_minimal.min'      => 'DP minimal tidak boleh kurang dari 1%.',
            'dp_persen_minimal.max'      => 'DP minimal tidak boleh lebih dari 100%.',
            'gambar_paket.max'           => 'Ukuran gambar terlalu besar. Maksimal adalah 5MB sebelum dikompresi.',
            'gambar_paket.image'         => 'File yang diunggah harus berupa gambar.',
            'gambar_paket.mimes'         => 'Format gambar harus jpg, jpeg, png, atau webp.',
        ]);

        try {
            // 3. PAKSA MENJADI GLOBAL: Set kantor_id dan periode_id menjadi null
            $validated['kantor_id'] = null; 
            $validated['periode_id'] = null;
            $validated['is_featured'] = $request->has('is_featured');

            if ($request->hasFile('gambar_paket')) {
                $validated['gambar_paket'] = $this->compressAndStore($request->file('gambar_paket'), 'pakets', 80);
            }

            PaketBimbingan::create($validated);
            return redirect()->route('paket-bimbingan.index')->with('success', 'Paket Global berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store Paket Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambah paket. Silakan periksa kembali isian Anda.');
        }
    }

    public function update(Request $request, PaketBimbingan $paketBimbingan)
    {
        // PROTEKSI AKSES UPDATE
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
            'harga_coret' => str_replace('.', '', $request->harga_coret),
        ]);

        $validated = $request->validate([
            'nama_paket'        => 'required|string|max:255',
            'nominal'           => 'required|numeric|min:0',
            'dp_persen_minimal' => 'required|integer|min:1|max:100', // <-- TAMBAHAN: Validasi DP Minimal
            'bisa_dicicil'      => 'required|boolean',
            'max_cicilan'       => 'required_if:bisa_dicicil,1|integer|min:1',
            'harga_coret'       => 'nullable|numeric|min:0',
            'durasi_jumlah'     => 'nullable|integer|min:1',
            'durasi_satuan'     => 'nullable|string|in:Bulan,Tahun',
            'deskripsi'         => 'nullable|string',
            'benefits'          => 'nullable|string',
            'target_peserta'    => 'nullable|string',
            'fasilitas'         => 'nullable|string',
            'is_featured'       => 'nullable|boolean',
            'label_populer'     => 'nullable|string|max:50',
            'urutan'            => 'nullable|integer',
            'gambar_paket'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'nama_paket.required'        => 'Nama paket wajib diisi.',
            'nominal.required'           => 'Harga promo wajib diisi.',
            'nominal.numeric'            => 'Harga harus berupa angka.',
            'dp_persen_minimal.required' => 'Persentase DP minimal wajib diisi.',
            'dp_persen_minimal.integer'  => 'DP minimal harus berupa angka bulat.',
            'dp_persen_minimal.min'      => 'DP minimal tidak boleh kurang dari 1%.',
            'dp_persen_minimal.max'      => 'DP minimal tidak boleh lebih dari 100%.',
            'gambar_paket.max'           => 'Ukuran gambar terlalu besar. Maksimal adalah 5MB.',
            'gambar_paket.image'         => 'File harus berupa gambar.',
            'gambar_paket.mimes'         => 'Format gambar harus jpg, jpeg, png, atau webp.',
        ]);

        try {
            // PASTIKAN TETAP GLOBAL SAAT DIUPDATE
            $validated['kantor_id'] = null; 
            $validated['periode_id'] = null;
            $validated['is_featured'] = $request->has('is_featured');

            if ($request->hasFile('gambar_paket')) {
                if ($paketBimbingan->gambar_paket) \Illuminate\Support\Facades\Storage::disk('public')->delete($paketBimbingan->gambar_paket);
                $validated['gambar_paket'] = $this->compressAndStore($request->file('gambar_paket'), 'pakets', 80);
            }

            $paketBimbingan->update($validated);
            return redirect()->route('paket-bimbingan.index')->with('success', 'Paket Global berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Paket Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui paket.');
        }
    }

    public function destroy(PaketBimbingan $paketBimbingan)
    {
        // PROTEKSI AKSES DELETE
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak.');
        }

        try {
            $paketBimbingan->delete();

            return redirect()->route('paket-bimbingan.index')
                ->with('success', 'Data Paket Bimbingan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('paket-bimbingan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}