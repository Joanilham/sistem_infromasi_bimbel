<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;

use App\Models\MasterData\Kantor;
use Illuminate\Http\Request;
use App\Http\Requests\MasterData\KantorRequest;

class KantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort    = in_array($request->input('sort'), ['id', 'nama_kantor']) ? $request->input('sort') : 'id';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        $kantors = Kantor::where(function ($q) use ($search) {
                $q->where('nama_kantor', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            })
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.kantor.index', compact('kantors', 'search', 'perPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KantorRequest $request)
    {
        Kantor::create($request->validated());

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KantorRequest $request, string $id)
    {
        $kantor = Kantor::findOrFail($id);

        $kantor->update($request->validated());

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Proteksi: jangan hapus jika ini kantor terakhir
        if (Kantor::count() <= 1) {
            return redirect()->route('kantor.index')
                ->with('error', 'Tidak dapat menghapus kantor terakhir. Minimal harus ada 1 kantor aktif di sistem.');
        }

        $kantor = Kantor::findOrFail($id);
        $kantor->delete(); // Soft delete — bisa dipulihkan

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil dihapus (dapat dipulihkan).');
    }

    /**
     * Pulihkan kantor yang terhapus (soft delete).
     */
    public function restore(string $id)
    {
        $kantor = Kantor::withTrashed()->findOrFail($id);
        $kantor->restore();

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil dipulihkan.');
    }
}



