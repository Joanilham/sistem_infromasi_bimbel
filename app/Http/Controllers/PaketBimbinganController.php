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

        $paketBimbingans = PaketBimbingan::inContext()
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
        return view('admin.paket_bimbingan.create');
    }

    public function edit(PaketBimbingan $paketBimbingan)
    {
        return view('admin.paket_bimbingan.edit', compact('paketBimbingan'));
    }

    public function store(Request $request)
    {
        // Bersihkan titik sebelum validasi
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
            'harga_coret' => str_replace('.', '', $request->harga_coret),
        ]);

        $validated = $request->validate([
            'nama_paket'    => 'required|string|max:255',
            'nominal'       => 'required|numeric|min:0',
            'harga_coret'   => 'nullable|numeric|min:0',
            'durasi_jumlah' => 'nullable|integer|min:1',
            'durasi_satuan' => 'nullable|string|in:Bulan,Tahun',
            'deskripsi'     => 'nullable|string',
            'benefits'      => 'nullable|string',
            'target_peserta'=> 'nullable|string',
            'fasilitas'     => 'nullable|string',
            'is_featured'   => 'nullable|boolean',
            'label_populer' => 'nullable|string|max:50',
            'urutan'        => 'nullable|integer',
            'gambar_paket'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ], [
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'nominal.required'    => 'Harga promo wajib diisi.',
            'nominal.numeric'     => 'Harga harus berupa angka.',
            'gambar_paket.max'    => 'Ukuran gambar terlalu besar. Maksimal adalah 1MB.',
            'gambar_paket.image'  => 'File yang diunggah harus berupa gambar.',
            'gambar_paket.mimes'  => 'Format gambar harus jpg, jpeg, png, atau webp.',
        ]);

        try {
            $validated['kantor_id'] = session('kantor_id');
            $validated['periode_id'] = session('periode_id');
            $validated['is_featured'] = $request->has('is_featured');

            if ($request->hasFile('gambar_paket')) {
                $validated['gambar_paket'] = $this->compressAndStore($request->file('gambar_paket'), 'pakets', 80);
            }

            PaketBimbingan::create($validated);
            return redirect()->route('paket-bimbingan.index')->with('success', 'Paket berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store Paket Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambah paket. Silakan periksa kembali isian Anda.');
        }
    }

    public function update(Request $request, PaketBimbingan $paketBimbingan)
    {
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
            'harga_coret' => str_replace('.', '', $request->harga_coret),
        ]);

        $validated = $request->validate([
            'nama_paket'    => 'required|string|max:255',
            'nominal'       => 'required|numeric|min:0',
            'harga_coret'   => 'nullable|numeric|min:0',
            'durasi_jumlah' => 'nullable|integer|min:1',
            'durasi_satuan' => 'nullable|string|in:Bulan,Tahun',
            'deskripsi'     => 'nullable|string',
            'benefits'      => 'nullable|string',
            'target_peserta'=> 'nullable|string',
            'fasilitas'     => 'nullable|string',
            'is_featured'   => 'nullable|boolean',
            'label_populer' => 'nullable|string|max:50',
            'urutan'        => 'nullable|integer',
            'gambar_paket'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ], [
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'nominal.required'    => 'Harga promo wajib diisi.',
            'nominal.numeric'     => 'Harga harus berupa angka.',
            'gambar_paket.max'    => 'Ukuran gambar terlalu besar. Maksimal adalah 1MB.',
            'gambar_paket.image'  => 'File yang diunggah harus berupa gambar.',
            'gambar_paket.mimes'  => 'Format gambar harus jpg, jpeg, png, atau webp.',
        ]);

        try {
            $validated['is_featured'] = $request->has('is_featured');

            if ($request->hasFile('gambar_paket')) {
                if ($paketBimbingan->gambar_paket) \Illuminate\Support\Facades\Storage::disk('public')->delete($paketBimbingan->gambar_paket);
                $validated['gambar_paket'] = $this->compressAndStore($request->file('gambar_paket'), 'pakets', 80);
            }

            $paketBimbingan->update($validated);
            return redirect()->route('paket-bimbingan.index')->with('success', 'Paket berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Paket Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui paket. Silakan periksa kembali isian Anda atau coba lagi nanti.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaketBimbingan $paketBimbingan)
    {
        $paketBimbingan->delete();

        return redirect()->route('paket-bimbingan.index')
            ->with('success', 'Data Paket Bimbingan berhasil dihapus.');
    }
}
