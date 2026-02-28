<?php

namespace App\Http\Controllers;

use App\Models\Kantor;
use Illuminate\Http\Request;

class KantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kantors = Kantor::all();
        return view('admin.kantor.index', compact('kantors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kantor' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        Kantor::create($validated);

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kantor = Kantor::findOrFail($id);

        $validated = $request->validate([
            'nama_kantor' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $kantor->update($validated);

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kantor = Kantor::findOrFail($id);
        $kantor->delete();

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil dihapus.');
    }
}
