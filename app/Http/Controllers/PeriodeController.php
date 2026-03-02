<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodes = Periode::all();
        return view('admin.periode.index', compact('periodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_periode' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        Periode::create($validated);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $periode = Periode::findOrFail($id);

        $validated = $request->validate([
            'tahun_periode' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $periode->update($validated);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $periode = Periode::findOrFail($id);
        $periode->delete();

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus.');
    }
}
