<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PesertaDidik;
use App\Models\PaketBimbingan;

class PesertaDidikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesertaDidiks = PesertaDidik::with('paketBimbingan')->where('status', 'Aktif')->latest()->get();
        $paketBimbingans = PaketBimbingan::all();
        return view('admin.peserta_didik.aktif', compact('pesertaDidiks', 'paketBimbingans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:50|unique:peserta_didiks,nomor_induk',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat_lengkap' => 'nullable|string',
            'asal_sekolah' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'no_telepon_ayah' => 'nullable|string|max:20',
            'no_telepon_ibu' => 'nullable|string|max:20',
            'informasi_dari' => 'nullable|string|max:255',
            'paket_bimbingan_id' => 'required|exists:paket_bimbingans,id',
            'kelompok_belajar' => 'required|string|max:255',
        ]);

        PesertaDidik::create($validated + ['status' => 'Aktif']);

        return redirect()->back()->with('success', 'Data Peserta Didik berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pesertaDidik = PesertaDidik::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:50|unique:peserta_didiks,nomor_induk,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat_lengkap' => 'nullable|string',
            'asal_sekolah' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'no_telepon_ayah' => 'nullable|string|max:20',
            'no_telepon_ibu' => 'nullable|string|max:20',
            'informasi_dari' => 'nullable|string|max:255',
            'paket_bimbingan_id' => 'required|exists:paket_bimbingans,id',
            'kelompok_belajar' => 'required|string|max:255',
            'status' => 'required|string|max:20',
        ]);

        $pesertaDidik->update($validated);

        return redirect()->back()->with('success', 'Data Peserta Didik berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pesertaDidik = PesertaDidik::findOrFail($id);
        $pesertaDidik->delete();

        return redirect()->back()->with('success', 'Data Peserta Didik berhasil dihapus!');
    }
}
