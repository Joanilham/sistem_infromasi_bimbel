<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort    = in_array($request->input('sort'), ['id', 'tahun_periode']) ? $request->input('sort') : 'id';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        $periodes = Periode::when($search, function ($q) use ($search) {
            $q->where('tahun_periode', 'like', "%{$search}%");
        })
        ->orderBy($sort, $order)
        ->paginate($perPage)
        ->withQueryString();

        return view('admin.periode.index', compact('periodes', 'search', 'perPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_periode' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
        ], [
            'tahun_periode.regex' => 'Format tahun periode tidak valid. Gunakan format YYYY/YYYY, contoh: 2025/2026.',
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
            'tahun_periode' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
        ], [
            'tahun_periode.regex' => 'Format tahun periode tidak valid. Gunakan format YYYY/YYYY, contoh: 2025/2026.',
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
        // Proteksi: jangan hapus jika ini periode terakhir
        if (Periode::count() <= 1) {
            return redirect()->route('periode.index')
                ->with('error', 'Tidak dapat menghapus periode terakhir. Minimal harus ada 1 periode aktif di sistem.');
        }

        $periode = Periode::findOrFail($id);
        $periode->delete(); // Soft delete — bisa dipulihkan

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus (dapat dipulihkan).');
    }

    /**
     * Pulihkan periode yang terhapus (soft delete).
     */
    public function restore(string $id)
    {
        $periode = Periode::withTrashed()->findOrFail($id);
        $periode->restore();

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dipulihkan.');
    }
}
