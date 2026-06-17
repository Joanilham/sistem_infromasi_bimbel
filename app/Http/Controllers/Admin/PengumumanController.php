<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        
        $pengumumans = Pengumuman::when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pengumuman.index', compact('pengumumans', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengumuman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengumuman', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Pengumuman::create($validated);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Berita & Informasi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($pengumuman->foto) {
                Storage::disk('public')->delete($pengumuman->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pengumuman', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $pengumuman->update($validated);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Berita & Informasi berhasil diperbarui.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Pengumuman $pengumuman)
    {
        $pengumuman->is_active = !$pengumuman->is_active;
        $pengumuman->save();

        $status = $pengumuman->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Berita & Informasi berhasil $status.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->foto) {
            Storage::disk('public')->delete($pengumuman->foto);
        }
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Berita & Informasi berhasil dihapus.');
    }
}

