<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\BankSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    public function index()
    {
        $ujians = Ujian::where('guru_id', Auth::id())
            ->latest()
            ->get();
        
        return view('guru.ujian.index', compact('ujians'));
    }

    public function create()
    {
        $bankSoals = BankSoal::where('guru_id', Auth::id())->get();
        return view('guru.ujian.create', compact('bankSoals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bank_soal_id' => 'nullable|exists:bank_soals,id',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date',
            'durasi' => 'nullable|integer|min:1',
            'acak_soal' => 'nullable|boolean',
            'tampilkan_hasil' => 'nullable|boolean',
        ]);

        $validated['guru_id'] = Auth::id();
        $validated['acak_soal'] = $request->has('acak_soal') ? 1 : 0;
        $validated['tampilkan_hasil'] = $request->has('tampilkan_hasil') ? 1 : 0;
        
        Ujian::create($validated);

        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil dibuat.');
    }

    public function show(string $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
        $hasilUjian = $ujian->hasil()->with('pesertaDidik')->get();
        
        return view('guru.ujian.show', compact('ujian', 'hasilUjian'));
    }

    public function edit(string $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
        $bankSoals = BankSoal::where('guru_id', Auth::id())->get();
        
        return view('guru.ujian.edit', compact('ujian', 'bankSoals'));
    }

    public function update(Request $request, string $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bank_soal_id' => 'nullable|exists:bank_soals,id',
            'status' => 'required|in:draft,published,closed',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date',
            'durasi' => 'nullable|integer|min:1',
            'acak_soal' => 'nullable|boolean',
            'tampilkan_hasil' => 'nullable|boolean',
        ]);

        $validated['acak_soal'] = $request->has('acak_soal') ? 1 : 0;
        $validated['tampilkan_hasil'] = $request->has('tampilkan_hasil') ? 1 : 0;

        $ujian->update($validated);

        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
        $ujian->delete();

        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil dihapus.');
    }

    public function publish(string $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
        $ujian->update(['status' => 'published']);

        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil dipublikasikan.');
    }

    public function close(string $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);
        $ujian->update(['status' => 'closed']);

        return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil ditutup.');
    }
}