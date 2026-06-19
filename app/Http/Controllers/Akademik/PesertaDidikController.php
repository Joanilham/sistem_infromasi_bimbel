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
        $kelompokBelajars = KelompokBelajar::query()->orderBy('nama_kelompok')->get();

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
        $kelompokBelajars = KelompokBelajar::query()->orderBy('nama_kelompok')->get();
        return view('admin.peserta_didik.create', compact('paketBimbingans', 'kelompokBelajars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PesertaDidikRequest $request)
    {
        $validated = $request->validated();

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, &$peserta) {
                $peserta = PesertaDidik::create($validated + [
                    'status' => 'Aktif',
                    'kantor_id' => session('kantor_id'),
                    'periode_id' => session('periode_id')
                ]);

                // Buat akun User
                \App\Models\User::create([
                    'name'             => $peserta->nama_lengkap,
                    'email'            => $validated['email'],
                    'username'         => $validated['email'],
                    'password'         => \Illuminate\Support\Facades\Hash::make($validated['password']),
                    'level'            => 'siswa',
                    'is_active'        => true,
                    'status'           => 'aktif',
                    'kantor_id'        => $peserta->kantor_id,
                    'periode_id'       => $peserta->periode_id,
                    'peserta_didik_id' => $peserta->id,
                ]);
            });

            // Kirim Notifikasi WA (Manual Add)
            $nomor = $peserta->no_telepon ?? $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu;
            if ($nomor) {
                $pesan = "📚 *Data Siswa Aktif*\n\nHalo *{$peserta->nama_lengkap}*,\n\nAdmin telah menambahkan data Anda ke dalam sistem Bimbingan Belajar. Anda sekarang telah terdaftar sebagai siswa aktif.\n\nAkun Login Anda:\nEmail: {$request->email}\nPassword: {$request->password}\n\nSelamat belajar dan sukses selalu! 🙏";
                \App\Services\WhatsAppService::sendAsync($nomor, $pesan);
            }

            \App\Services\CacheService::clearPesertaCache();

            return redirect()->route('keuangan.pembayaran.show', $peserta->id)->with('success', 'Data Peserta Didik dan Akun berhasil ditambahkan. Silakan atur pembayaran siswa di sini.');
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
        $kelompokBelajars = KelompokBelajar::query()->orderBy('nama_kelompok')->get();
        return view('admin.peserta_didik.edit', compact('pesertaDidik', 'paketBimbingans', 'kelompokBelajars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PesertaDidikRequest $request, string $id)
    {
        $pesertaDidik = PesertaDidik::findOrFail($id);

        $validated = $request->validated();

        // Hapus field keluar jika status bukan Keluar atau Lulus
        if (!in_array($validated['status'], ['Keluar', 'Lulus'])) {
            $validated['tanggal_keluar'] = null;
            $validated['alasan_keluar']  = null;
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($pesertaDidik, $validated, $request) {
                $pesertaDidik->update($validated);

                // Update User
                $user = \App\Models\User::where('peserta_didik_id', $pesertaDidik->id)->first();
                if ($user) {
                    $userData = [
                        'name'     => $validated['nama_lengkap'],
                        'email'    => $validated['email'],
                        'username' => $validated['email'],
                        'status'   => strtolower($validated['status']),
                    ];
                    if (!empty($validated['password'])) {
                        $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
                    }
                    $user->update($userData);
                } else {
                    // Jika sebelumnya belum punya akun, buatkan
                    if (!empty($validated['email']) && !empty($validated['password'])) {
                        \App\Models\User::create([
                            'name'             => $pesertaDidik->nama_lengkap,
                            'email'            => $validated['email'],
                            'username'         => $validated['email'],
                            'password'         => \Illuminate\Support\Facades\Hash::make($validated['password']),
                            'level'            => 'siswa',
                            'is_active'        => true,
                            'status'           => strtolower($validated['status']),
                            'kantor_id'        => $pesertaDidik->kantor_id,
                            'periode_id'       => $pesertaDidik->periode_id,
                            'peserta_didik_id' => $pesertaDidik->id,
                        ]);
                    }
                }
            });

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
        
        \Illuminate\Support\Facades\DB::transaction(function () use ($pesertaDidik) {
            $user = \App\Models\User::where('peserta_didik_id', $pesertaDidik->id)->first();
            if ($user) {
                $user->delete();
            }
            $pesertaDidik->delete();
        });

        \App\Services\CacheService::clearPesertaCache();

        return redirect()->back()->with('success', 'Data Peserta Didik beserta Akun berhasil dihapus!');
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



