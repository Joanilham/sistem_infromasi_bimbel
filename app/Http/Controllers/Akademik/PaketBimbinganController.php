<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;

use App\Models\Akademik\PaketBimbingan;
use Illuminate\Http\Request;
use App\Http\Requests\Akademik\PaketBimbinganRequest;

class PaketBimbinganController extends Controller
{
    use \App\Traits\HandlesImageUpload;

    public function index(Request $request)
    {
        $search     = $request->input('search', '');
        $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort       = in_array($request->input('sort'), ['id', 'nama_paket', 'urutan']) ? $request->input('sort') : 'urutan';
        $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'asc';

        // 1. HAPUS inContext() agar Admin bisa melihat semua paket secara global
        $paketBimbingans = PaketBimbingan::query()
            ->when($search, fn($q) =>
                $q->where('nama_paket', 'like', "%{$search}%")
            )
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.paket_bimbingan.index', compact('paketBimbingans'));
    }

    public function create()
    {
        // 2. PROTEKSI AKSES: Hanya Super Admin / Admin Pusat yang boleh akses halaman tambah
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak. Hanya Pusat yang berhak menambah Paket Bimbingan.');
        }

        return view('admin.paket_bimbingan.create');
    }

    public function edit(PaketBimbingan $paketBimbingan)
    {
        // PROTEKSI AKSES EDIT
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak. Hanya Pusat yang berhak mengedit Paket Bimbingan.');
        }

        return view('admin.paket_bimbingan.edit', compact('paketBimbingan'));
    }

    public function store(PaketBimbinganRequest $request)
    {
        // PROTEKSI AKSES STORE
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak.');
        }

        $validated = $request->validated();

        try {
            // 3. PAKSA MENJADI GLOBAL: Set kantor_id dan periode_id menjadi null
            $validated['kantor_id'] = null; 
            $validated['periode_id'] = null;
            $validated['is_featured'] = $request->has('is_featured');

            if ($request->hasFile('gambar_paket')) {
                $validated['gambar_paket'] = $this->compressAndStore($request->file('gambar_paket'), 'pakets', 80);
            }

            PaketBimbingan::create($validated);
            return redirect()->route('paket-bimbingan.index')->with('success', 'Paket Global berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store Paket Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambah paket. Silakan periksa kembali isian Anda.');
        }
    }

    public function update(PaketBimbinganRequest $request, PaketBimbingan $paketBimbingan)
    {
        // PROTEKSI AKSES UPDATE
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak.');
        }

        $validated = $request->validated();

        try {
            // PASTIKAN TETAP GLOBAL SAAT DIUPDATE
            $validated['kantor_id'] = null; 
            $validated['periode_id'] = null;
            $validated['is_featured'] = $request->has('is_featured');

            if ($request->hasFile('gambar_paket')) {
                if ($paketBimbingan->gambar_paket) \Illuminate\Support\Facades\Storage::disk('public')->delete($paketBimbingan->gambar_paket);
                $validated['gambar_paket'] = $this->compressAndStore($request->file('gambar_paket'), 'pakets', 80);
            }

            $paketBimbingan->update($validated);
            return redirect()->route('paket-bimbingan.index')->with('success', 'Paket Global berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Paket Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui paket.');
        }
    }

    public function destroy(PaketBimbingan $paketBimbingan)
    {
        // PROTEKSI AKSES DELETE
        if (strtolower(auth()->user()->level) !== 'admin' && strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses Ditolak.');
        }

        try {
            $paketBimbingan->delete();

            return redirect()->route('paket-bimbingan.index')
                ->with('success', 'Data Paket Bimbingan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('paket-bimbingan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}


