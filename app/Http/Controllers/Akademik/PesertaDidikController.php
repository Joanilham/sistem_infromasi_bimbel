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
     * Daftar peserta didik yang sudah lulus.
     */
    public function lulus(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        
        $pesertaDidiks = $this->pesertaDidikService->getLulusData($request);
        $paketBimbingans = PaketBimbingan::get();
        $kelompokBelajars = KelompokBelajar::get();

        return view('admin.peserta_didik.lulus', compact('pesertaDidiks', 'paketBimbingans', 'kelompokBelajars', 'search', 'perPage'));
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

        $periodeId = session('periode_id');
        if (!$periodeId || !\App\Models\MasterData\Periode::where('id', $periodeId)->exists()) {
            return back()->withInput()->with('error', 'Konteks Periode (ID: ' . ($periodeId ?? 'kosong') . ') tidak ditemukan di database. Silakan pilih ulang Periode di menu atas.');
        }

        $kantorId = session('kantor_id');
        if (!$kantorId || $kantorId === 'all' || !\App\Models\MasterData\Kantor::where('id', $kantorId)->exists()) {
            return back()->withInput()->with('error', 'Konteks Kantor/Cabang (ID: ' . ($kantorId ?? 'kosong') . ') tidak ditemukan atau tidak valid. Silakan pilih ulang Cabang di menu atas.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, &$peserta, $kantorId, $periodeId) {
                $peserta = PesertaDidik::create($validated + [
                    'status' => 'Aktif',
                    'kantor_id' => $kantorId,
                    'periode_id' => $periodeId
                ]);

                // Buat akun User jika email dan password diisi
                if (!empty($validated['email']) && !empty($validated['password'])) {
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
                }
            });

            // Kirim Notifikasi WA (Manual Add)
            $nomor = $peserta->no_telepon ?? $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu;
            if ($nomor) {
                $pesan = "📚 *Data Siswa Aktif*\n\nHalo *{$peserta->nama_lengkap}*,\n\nAdmin telah menambahkan data Anda ke dalam sistem Bimbingan Belajar. Anda sekarang telah terdaftar sebagai siswa aktif.";
                if (!empty($request->email) && !empty($request->password)) {
                    $pesan .= "\n\nAkun Login Anda:\nEmail: {$request->email}\nPassword: {$request->password}";
                }
                $pesan .= "\n\nSelamat belajar dan sukses selalu! 🙏";
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

    public function destroy(string $id)
    {
        if (!in_array(strtolower(auth()->user()->level), ['admin', 'super admin'])) {
            abort(403, 'Akses Ditolak.');
        }

        $pesertaDidik = PesertaDidik::inContext()->findOrFail($id);

        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

            \Illuminate\Support\Facades\DB::transaction(function () use ($pesertaDidik) {
                $user = \App\Models\User::where('peserta_didik_id', $pesertaDidik->id)->first();

                if ($user) {
                    // 1. Hapus data CBT (cbt_peserta_jawabans → cbt_pesertas)
                    $cbtPesertaIds = \Illuminate\Support\Facades\DB::table('cbt_pesertas')
                        ->where('user_id', $user->id)->pluck('id');
                    if ($cbtPesertaIds->isNotEmpty()) {
                        \Illuminate\Support\Facades\DB::table('cbt_peserta_jawabans')
                            ->whereIn('cbt_peserta_id', $cbtPesertaIds)->delete();
                        \Illuminate\Support\Facades\DB::table('cbt_pesertas')
                            ->where('user_id', $user->id)->delete();
                    }

                    // 2. Hapus data chat (messages → participants → conversations)
                    $conversationIds = \Illuminate\Support\Facades\DB::table('conversation_user')
                        ->where('user_id', $user->id)->pluck('conversation_id');
                    if ($conversationIds->isNotEmpty()) {
                        \Illuminate\Support\Facades\DB::table('messages')
                            ->whereIn('conversation_id', $conversationIds)->delete();
                        \Illuminate\Support\Facades\DB::table('conversation_user')
                            ->whereIn('conversation_id', $conversationIds)->delete();
                        \Illuminate\Support\Facades\DB::table('conversations')
                            ->whereIn('id', $conversationIds)->delete();
                    }

                    // 3. Hapus user
                    $user->delete();
                }

                // 4. Hapus pembayaran_siswa beserta transaksi_pembayaran-nya
                $pembayaranList = \App\Models\Keuangan\PembayaranSiswa::where('peserta_didik_id', $pesertaDidik->id)->get();
                foreach ($pembayaranList as $pembayaran) {
                    \App\Models\Keuangan\TransaksiPembayaran::where('pembayaran_siswa_id', $pembayaran->id)->delete();
                    $pembayaran->delete();
                }

                // 5. Hapus absensi
                \Illuminate\Support\Facades\DB::table('absensis')
                    ->where('peserta_didik_id', $pesertaDidik->id)->delete();

                // 6. Hapus peserta didik secara permanen
                $pesertaDidik->delete();
            });

            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            \App\Services\CacheService::clearPesertaCache();

            return redirect()->back()->with('success', 'Data Peserta Didik berhasil dihapus secara permanen!');
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'Gagal hapus permanen! Peserta Didik ini masih memiliki data terkait di sistem yang tidak dapat dihapus otomatis.');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Gagal menghapus secara permanen: ' . $e->getMessage());
        }
    }

    /**
     * Export keluar students to Excel (XML Spreadsheet 2003).
     */
    public function exportKeluar()
    {
        return $this->pesertaDidikExport->exportKeluar();
    }

    /**
     * Export lulus students to Excel (XML Spreadsheet 2003).
     */
    public function exportLulus()
    {
        return $this->pesertaDidikExport->exportLulus();
    }

    /**
     * Export active students to Excel (XML Spreadsheet 2003).
     */
    public function export()
    {
        return $this->pesertaDidikExport->exportAktif();
    }
}



