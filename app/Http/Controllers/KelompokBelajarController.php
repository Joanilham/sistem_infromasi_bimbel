<?php

namespace App\Http\Controllers;

use App\Models\KelompokBelajar;
use Illuminate\Http\Request;

class KelompokBelajarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100])
                    ? (int) $request->input('per_page')
                    : 10;

        $sort    = in_array($request->input('sort'), ['id', 'nama_kelompok']) ? $request->input('sort') : 'id';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        $kelompokBelajars = KelompokBelajar::inContext()
            ->when($search, fn($q) =>
                $q->where('nama_kelompok', 'like', "%{$search}%")
            )
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.kelompok_belajar.index', compact('kelompokBelajars', 'search', 'perPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelompok' => 'required|string|max:255',
        ]);

        $validated['kantor_id'] = session('kantor_id');
        $validated['periode_id'] = session('periode_id');

        KelompokBelajar::create($validated);

        return redirect()->route('kelompok-belajar.index')
            ->with('success', 'Data Kelompok Belajar berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KelompokBelajar $kelompokBelajar)
    {
        $validated = $request->validate([
            'nama_kelompok' => 'required|string|max:255',
        ]);

        $kelompokBelajar->update($validated);

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
