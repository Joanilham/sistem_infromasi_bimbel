<?php

namespace App\Http\Controllers;

use App\Models\PaketBimbingan;
use Illuminate\Http\Request;

class PaketBimbinganController extends Controller
{
    use \App\Traits\HandlesImageUpload;

    public function index()
    {
        $paketBimbingans = PaketBimbingan::inContext()->orderBy('urutan')->get();
        return view('admin.paket_bimbingan.index', compact('paketBimbingans'));
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
            'is_featured'   => 'nullable|boolean',
            'label_populer' => 'nullable|string|max:50',
            'urutan'        => 'nullable|integer',
            'gambar_paket'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
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
            return back()->withInput()->with('error', 'Gagal menambah paket.');
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
            'is_featured'   => 'nullable|boolean',
            'label_populer' => 'nullable|string|max:50',
            'urutan'        => 'nullable|integer',
            'gambar_paket'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
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
            return back()->withInput()->with('error', 'Gagal memperbarui paket.');
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
