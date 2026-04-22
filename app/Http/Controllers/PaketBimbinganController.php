<?php

namespace App\Http\Controllers;

use App\Models\PaketBimbingan;
use Illuminate\Http\Request;

class PaketBimbinganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paketBimbingans = PaketBimbingan::inContext()->latest()->get();
        return view('admin.paket_bimbingan.index', compact('paketBimbingans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Bersihkan titik sebelum validasi
        if ($request->has('nominal')) {
            $request->merge([
                'nominal' => str_replace('.', '', $request->nominal)
            ]);
        }

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'nominal'    => 'required|numeric|min:0',
        ]);

        $validated['kantor_id'] = session('kantor_id');
        $validated['periode_id'] = session('periode_id');

        PaketBimbingan::create($validated);

        return redirect()->route('paket-bimbingan.index')
            ->with('success', 'Data Paket Bimbingan berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaketBimbingan $paketBimbingan)
    {
        // Bersihkan titik sebelum validasi
        if ($request->has('nominal')) {
            $request->merge([
                'nominal' => str_replace('.', '', $request->nominal)
            ]);
        }

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'nominal'    => 'required|numeric|min:0',
        ]);

        $paketBimbingan->update($validated);

        return redirect()->route('paket-bimbingan.index')
            ->with('success', 'Data Paket Bimbingan berhasil diperbarui.');
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
