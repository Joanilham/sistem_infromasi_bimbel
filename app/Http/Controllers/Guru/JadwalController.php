<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Tampilan jadwal mingguan milik guru (kalender grid).
     */
    public function index()
    {
        $jadwal = Jadwal::where('guru_id', Auth::id())
            ->with(['rombel', 'mataPelajaran'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('guru.jadwal.index', compact('jadwal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari'       => 'required|in:' . implode(',', Jadwal::HARI_LIST),
            'jam_mulai'  => 'required',
            'jam_selesai' => 'required',
            'kelas'      => 'required|string',
            'mapel'      => 'required|string',
        ]);

        $validated['guru_id'] = Auth::id();

        // Simpan sebagai JadwalMapel (legacy) — guru hanya pakai fitur sederhana
        \App\Models\JadwalMapel::create($validated);

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $jadwal = \App\Models\JadwalMapel::where('guru_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'hari'       => 'required|string',
            'jam_mulai'  => 'required',
            'jam_selesai' => 'required',
            'kelas'      => 'required|string',
            'mapel'      => 'required|string',
        ]);

        $jadwal->update($validated);

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $jadwal = \App\Models\JadwalMapel::where('guru_id', Auth::id())->findOrFail($id);
        $jadwal->delete();

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}