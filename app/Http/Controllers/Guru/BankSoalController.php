<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSoalController extends Controller
{
    public function index()
    {
        $bankSoals = BankSoal::where('guru_id', Auth::id())
            ->latest()
            ->get();
        
        return view('guru.bank-soal.index', compact('bankSoals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['guru_id'] = Auth::id();
        
        BankSoal::create($validated);

        return redirect()->route('guru.bank-soal.index')->with('success', 'Bank soa berhasil dibuat.');
    }

    public function show(string $id)
    {
        $bankSoal = BankSoal::where('guru_id', Auth::id())->findOrFail($id);
        $soals = $bankSoal->soals()->orderBy('id')->get();
        
        return view('guru.bank-soal.show', compact('bankSoal', 'soals'));
    }

    public function update(Request $request, string $id)
    {
        $bankSoal = BankSoal::where('guru_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $bankSoal->update($validated);

        return redirect()->route('guru.bank-soal.index')->with('success', 'Bank soa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bankSoal = BankSoal::where('guru_id', Auth::id())->findOrFail($id);
        $bankSoal->delete();

        return redirect()->route('guru.bank-soal.index')->with('success', 'Bank soa berhasil dihapus.');
    }

    // Manajemen soa dalam bank soa
    public function storeSoal(Request $request, string $bankSoalId)
    {
        $bankSoal = BankSoal::where('guru_id', Auth::id())->findOrFail($bankSoalId);

        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:pilihan_ganda,essay',
            'opsi_a' => 'nullable|string',
            'opsi_b' => 'nullable|string',
            'opsi_c' => 'nullable|string',
            'opsi_d' => 'nullable|string',
            'jawaban_benar' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'poin' => 'nullable|integer|min:1',
        ]);

        $validated['bank_soal_id'] = $bankSoal->id;
        $validated['poin'] = $validated['poin'] ?? 10;

        // Simpan opsi sebagai JSON
        $validated['opsi_a'] = json_encode(['text' => $request->opsi_a]);
        $validated['opsi_b'] = json_encode(['text' => $request->opsi_b]);
        $validated['opsi_c'] = json_encode(['text' => $request->opsi_c]);
        $validated['opsi_d'] = json_encode(['text' => $request->opsi_d]);

        $bankSoal->soals()->create($validated);

        // Update jumlah soa
        $bankSoal->update(['jumlah_soal' => $bankSoal->soals()->count()]);

        return redirect()->route('guru.bank-soal.show', $bankSoal->id)->with('success', 'Soa berhasil ditambahkan.');
    }

    public function destroySoal(string $bankSoalId, string $soalId)
    {
        $bankSoal = BankSoal::where('guru_id', Auth::id())->findOrFail($bankSoalId);
        $soal = $bankSoal->soals()->findOrFail($soalId);
        $soal->delete();

        // Update jumlah soa
        $bankSoal->update(['jumlah_soal' => $bankSoal->soals()->count()]);

        return redirect()->route('guru.bank-soal.show', $bankSoal->id)->with('success', 'Soa berhasil dihapus.');
    }
}