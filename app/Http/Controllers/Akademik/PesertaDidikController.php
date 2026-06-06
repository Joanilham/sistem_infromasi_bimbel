<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\PesertaDidikRequest;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;
use App\Services\PesertaDidikService;
use App\Exports\PesertaDidikExport;

class PesertaDidikController extends Controller
{
    public function __construct(
        protected PesertaDidikService $pesertaDidikService,
        protected PesertaDidikExport $pesertaDidikExport
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        
        $pesertaDidiks = $this->pesertaDidikService->getAktifData($request);
        $paketBimbingans = PaketBimbingan::get();
        $kelompokBelajars = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();

        return view('admin.peserta_didik.aktif', compact('pesertaDidiks', 'paketBimbingans', 'kelompokBelajars', 'search', 'perPage'));
    }

    /**
     * Daftar peserta didik yang sudah keluar.
     */
    public function keluar(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        
        $pesertaDidiks = $this->pesertaDidikService->getKeluarData($request);
        $paketBimbingans = PaketBimbingan::get();
        $kelompokBelajars = KelompokBelajar::get();

        return view('admin.peserta_didik.keluar', compact('pesertaDidiks', 'paketBimbingans', 'kelompokBelajars', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paketBimbingans  = PaketBimbingan::get();
        $kelompokBelajars = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();
        return view('admin.peserta_didik.create', compact('paketBimbingans', 'kelompokBelajars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PesertaDidikRequest $request)
    {
        $validated = $request->validated();

        try {
            $peserta = PesertaDidik::create($validated + [
                'status' => 'Aktif',
                'kantor_id' => session('kantor_id'),
                'periode_id' => session('periode_id')
            ]);

            // Kirim Notifikasi WA (Manual Add)
            $nomor = $peserta->no_telepon ?? $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu;
            if ($nomor) {
                $pesan = "📚 *Data Siswa Aktif*\n\nHalo *{$peserta->nama_lengkap}*,\n\nAdmin telah menambahkan data Anda ke dalam sistem Bimbingan Belajar. Anda sekarang telah terdaftar sebagai siswa aktif.\n\nSelamat belajar dan sukses selalu! 🙏";
                \App\Services\WhatsAppService::sendAsync($nomor, $pesan);
            }

            \App\Services\CacheService::clearPesertaCache();

            return redirect()->route('peserta-didik.index')->with('success', 'Data Peserta Didik berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Student Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menambahkan data siswa.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pesertaDidik     = PesertaDidik::findOrFail($id);
        $paketBimbingans  = PaketBimbingan::get();
        $kelompokBelajars = KelompokBelajar::inContext()->orderBy('nama_kelompok')->get();
        return view('admin.peserta_didik.edit', compact('pesertaDidik', 'paketBimbingans', 'kelompokBelajars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PesertaDidikRequest $request, string $id)
    {
        $pesertaDidik = PesertaDidik::findOrFail($id);

        $validated = $request->validated();

        // Hapus field keluar jika status bukan Keluar
        if ($validated['status'] !== 'Keluar') {
            $validated['tanggal_keluar'] = null;
            $validated['alasan_keluar']  = null;
        }

        try {
            $pesertaDidik->update($validated);
            \App\Services\CacheService::clearPesertaCache();
            return redirect()->route('peserta-didik.index')->with('success', 'Data Peserta Didik berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Student Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data siswa.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pesertaDidik = PesertaDidik::inContext()->findOrFail($id);
        $pesertaDidik->delete();

        \App\Services\CacheService::clearPesertaCache();

        return redirect()->back()->with('success', 'Data Peserta Didik berhasil dihapus!');
    }

    /**
     * Export keluar students to Excel (XML Spreadsheet 2003).
     */
    public function exportKeluar()
    {
        return $this->pesertaDidikExport->exportKeluar();
    }

    /**
     * Export active students to Excel (XML Spreadsheet 2003).
     */
    public function export()
    {
        return $this->pesertaDidikExport->exportAktif();
    }
}



