<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalMapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = JadwalMapel::where('guru_id', Auth::id())
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
        
        return view('guru.jadwal.index', compact('jadwal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kelas' => 'required|string',
            'mapel' => 'required|string',
        ]);

        $validated['guru_id'] = Auth::id();
        
        JadwalMapel::create($validated);

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $jadwal = JadwalMapel::where('guru_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kelas' => 'required|string',
            'mapel' => 'required|string',
        ]);

        $jadwal->update($validated);

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $jadwal = JadwalMapel::where('guru_id', Auth::id())->findOrFail($id);
        $jadwal->delete();

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}