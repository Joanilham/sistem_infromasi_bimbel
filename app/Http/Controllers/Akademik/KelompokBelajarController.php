<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;

use App\Models\Akademik\KelompokBelajar;
use Illuminate\Http\Request;
use App\Http\Requests\Akademik\KelompokBelajarRequest;

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

        // 1. HAPUS inContext() agar Admin bisa melihat semua kelompok belajar secara global
        $kelompokBelajars = KelompokBelajar::query()
            ->withCount('pesertaDidiks')
            ->when($search, fn($q) =>
                $q->where('nama_kelompok', 'like', "%{$search}%")
            )
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.kelompok_belajar.index', compact('kelompokBelajars', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // PROTEKSI AKSES CREATE
        if (!in_array(strtolower(auth()->user()->level), ['admin', 'super admin'])) {
            abort(403, 'Akses Ditolak.');
        }
        return view('admin.kelompok_belajar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KelompokBelajarRequest $request)
    {
        // 2. PROTEKSI AKSES: Hanya Admin/Super Admin yang boleh menambah data
        if (!in_array(strtolower(auth()->user()->level), ['admin', 'super admin'])) {
            abort(403, 'Akses Ditolak. Hanya Admin yang berhak menambah Kelompok Belajar.');
        }

        $validated = $request->validated();

        // 3. PAKSA MENJADI GLOBAL: Set kantor_id dan periode_id menjadi null
        $validated['kantor_id'] = null;
        $validated['periode_id'] = null;

        KelompokBelajar::create($validated);

        return redirect()->route('kelompok-belajar.index')
            ->with('success', 'Data Kelompok Belajar Global berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KelompokBelajar $kelompokBelajar)
    {
        // PROTEKSI AKSES EDIT
        if (!in_array(strtolower(auth()->user()->level), ['admin', 'super admin'])) {
            abort(403, 'Akses Ditolak.');
        }
        return view('admin.kelompok_belajar.edit', compact('kelompokBelajar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KelompokBelajarRequest $request, KelompokBelajar $kelompokBelajar)
    {
        // PROTEKSI AKSES UPDATE
        if (!in_array(strtolower(auth()->user()->level), ['admin', 'super admin'])) {
            abort(403, 'Akses Ditolak. Hanya Admin yang berhak mengubah Kelompok Belajar.');
        }

        $validated = $request->validated();

        // PASTIKAN TETAP GLOBAL SAAT DIUPDATE
        $validated['kantor_id'] = null;
        $validated['periode_id'] = null;

        $kelompokBelajar->update($validated);

        return redirect()->route('kelompok-belajar.index')
            ->with('success', 'Data Kelompok Belajar Global berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KelompokBelajar $kelompokBelajar)
    {
        // PROTEKSI AKSES DELETE
        if (!in_array(strtolower(auth()->user()->level), ['admin', 'super admin'])) {
            abort(403, 'Akses Ditolak.');
        }

        $kelompokBelajar->delete();

        return redirect()->route('kelompok-belajar.index')
            ->with('success', 'Data Kelompok Belajar berhasil dihapus.');
    }
}


