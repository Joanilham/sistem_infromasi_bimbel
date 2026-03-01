<?php

namespace App\Http\Controllers;

use App\Models\KelompokBelajar;
use Illuminate\Http\Request;

class KelompokBelajarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelompokBelajars = KelompokBelajar::latest()->get();
        return view('admin.kelompok_belajar.index', compact('kelompokBelajars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
        ]);

        KelompokBelajar::create($request->all());

        return redirect()->route('kelompok-belajar.index')
            ->with('success', 'Data Kelompok Belajar berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KelompokBelajar $kelompokBelajar)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
        ]);

        $kelompokBelajar->update($request->all());

        return redirect()->route('kelompok-belajar.index')
            ->with('success', 'Data Kelompok Belajar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KelompokBelajar $kelompokBelajar)
    {
        $kelompokBelajar->delete();

        return redirect()->route('kelompok-belajar.index')
            ->with('success', 'Data Kelompok Belajar berhasil dihapus.');
    }
}
