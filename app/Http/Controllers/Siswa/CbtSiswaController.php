<?php

namespace App\Http\Controllers\Siswa;
use App\Models\Akademik\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtUjian;
use App\Models\CBT\CbtPeserta;
use App\Models\CBT\CbtPesertaJawaban;
use App\Models\CBT\CbtUjianAssign;
use App\Services\CbtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CbtSiswaController extends Controller
{
    public function __construct(protected CbtService $cbtService)
    {}
    /**
     * S3-F1: Daftar Ujian Tersedia
     */
    public function index()
    {
        $user = Auth::user();
        $pesertaDidik = $user->pesertaDidik;
        $kelompokId = $pesertaDidik ? $pesertaDidik->kelompok_belajar_id : null;

        // Cari ID ujian yang di-assign ke siswa ini (personal atau kelas)
        $assignedUjianIds = $this->getAssignedUjianIds($user->id, $kelompokId);

        // S3-F1: Daftar Ujian Selesai / Lewat Waktu
        $ujianSelesaiIds = $this->getUjianSelesaiIds($user->id, $assignedUjianIds);

        // S3-F1: Daftar Ujian Aktif (exclude selesai)
        $ujianAktif = CbtUjian::whereIn('id', $assignedUjianIds)
            ->aktif()
            ->orderBy('waktu_mulai', 'asc')
            ->get()
            ->reject(fn($ujian) => in_array($ujian->id, $ujianSelesaiIds));

        // S3-F1: Daftar Ujian Mendatang (exclude selesai)
        $ujianMendatang = CbtUjian::whereIn('id', $assignedUjianIds)
            ->mendatang()
            ->orderBy('waktu_mulai', 'asc')
            ->get()
            ->reject(fn($ujian) => in_array($ujian->id, $ujianSelesaiIds));

        // S3-F1: Daftar Ujian Selesai (Detail)
        $ujianSelesai = CbtUjian::whereIn('id', $ujianSelesaiIds)
            ->orderBy('waktu_selesai', 'desc')
            ->get();

        // Ambil data sesi untuk passing ke view
        $sesis = CbtPeserta::where('user_id', $user->id)->get()->keyBy('cbt_ujian_id');

        return view('siswa.cbt.index', compact('ujianAktif', 'ujianMendatang', 'ujianSelesai', 'sesis'));
    }

    /**
     * S4-F2: Riwayat Ujian Siswa
     */
    public function riwayat()
    {
        $user = Auth::user();
        $pesertaDidik = $user ? $user->pesertaDidik : null;
        $pembayaranBelumLunas = false;
        $kekurangan = 0;

        // if ($pesertaDidik) {
        //     $pembayaran = $pesertaDidik->pembayaran()->first();
        //     if ($pembayaran && !$pembayaran->lunas && !$pembayaran->dispensasi) {
        //         $pembayaranBelumLunas = true;
        //         $kekurangan = $pembayaran->kekurangan;
        //     }
        // }

        $pesertas = CbtPeserta::where('user_id', Auth::id())
            ->with(['ujian'])
            ->withCount(['jawabans as belum_dikoreksi_count' => function($q) {
                $q->whereNull('is_benar');
            }])
            ->whereIn('status', ['selesai', 'timeout'])
            ->orderBy('waktu_selesai', 'desc')
            ->paginate(15);

        return view('siswa.cbt.riwayat', compact('pesertas', 'pembayaranBelumLunas', 'kekurangan'));
    }

    /**
     * Tata Tertib (Pre-exam)
     */
    public function show($id)
    {
        $user = Auth::user();
        $pesertaDidik = $user->pesertaDidik;
        $kelompokId = $pesertaDidik ? $pesertaDidik->kelompok_belajar_id : null;
        $assignedUjianIds = $this->getAssignedUjianIds($user->id, $kelompokId);
        
        if (!in_array($id, $assignedUjianIds)) {
            abort(403, 'Anda tidak terdaftar untuk ujian ini.');
        }

        $ujian = CbtUjian::withCount('ujianSoals as soals_count')->findOrFail($id);
        
        // Cari sesi yang sedang aktif
        $sesiAktif = CbtPeserta::where('cbt_ujian_id', $ujian->id)
            ->where('user_id', Auth::id())
            ->where('status', 'mengerjakan')
            ->first();

        if ($sesiAktif) {
            return redirect()->route('siswa.ujian.soal', [$sesiAktif->id, 1]);
        }

        // Cek riwayat untuk info di tata tertib
        $riwayat = CbtPeserta::where('cbt_ujian_id', $ujian->id)
            ->where('user_id', Auth::id())
            ->orderBy('attempt_ke', 'desc')
            ->get();

        $canAttempt = $ujian->canAttempt(Auth::id());
        $attemptKe = $riwayat->count() + 1;

        return view('siswa.cbt.tata-tertib', [
            'ujian' => $ujian,
            'riwayat' => $riwayat,
            'canAttempt' => $canAttempt,
            'attemptKe' => $attemptKe
        ]);
    }

    /**
     * Memulai Ujian (Generate Sesi & Soal)
     */
    public function mulai(Request $request, $id)
    {
        $userId = Auth::id();
        $lockKey = "mulai_ujian_{$id}_{$userId}";

        // Coba dapatkan lock selama 10 detik, cegah spam klik (Race Condition)
        $lock = Cache::lock($lockKey, 10);
        if (!$lock->get()) {
            return back()->with('error', 'Sistem sedang memproses permintaan Anda, harap tunggu sebentar.');
        }

        try {
            $user = Auth::user();
            $pesertaDidik = $user->pesertaDidik;
            $kelompokId = $pesertaDidik ? $pesertaDidik->kelompok_belajar_id : null;
            $assignedUjianIds = $this->getAssignedUjianIds($userId, $kelompokId);
            
            if (!in_array($id, $assignedUjianIds)) {
                return back()->with('error', 'Anda tidak terdaftar untuk mengikuti ujian ini.');
            }

            $ujian = CbtUjian::with(['ujianSoals.bankSoal.opsiJawabans'])->findOrFail($id);

            if (!$ujian->is_aktif) {
                return back()->with('error', 'Ujian belum aktif atau sudah berakhir.');
            }

            if ($ujian->ujianSoals->isEmpty()) {
                return back()->with('error', 'Ujian belum memiliki soal.');
            }

            if ($ujian->token) {
                $request->validate(['token' => 'required|string']);
                if ($request->token !== $ujian->token) {
                    return back()->with('error', 'Token ujian tidak valid.');
                }
            }

            // 1. Cek Sesi Aktif
            $sesiAktif = CbtPeserta::where('cbt_ujian_id', $ujian->id)
                ->where('user_id', $userId)
                ->where('status', 'mengerjakan')
                ->first();

            if ($sesiAktif) {
                return redirect()->route('siswa.ujian.soal', [$sesiAktif->id, 1]);
            }

            // 2. Cek Limit Attempt
            if (!$ujian->canAttempt($userId)) {
                return redirect()->route('siswa.ujian.index')->with('error', 'Anda telah mencapai batas maksimal percobaan untuk ujian ini.');
            }

            $sesi = $this->cbtService->initiateSesi($ujian, $userId, [
                'session_token' => session()->getId(),
                'ip_address'    => $request->ip(),
            ]);

            $sesi->load('ujian');
            Cache::put("cbt_sesi:{$sesi->id}", $sesi, 3600);

            return redirect()->route('siswa.ujian.soal', [$sesi->id, 1]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulai ujian: ' . $e->getMessage());
        } finally {
            $lock->release();
        }
    }

    /**
     * S3-F2: Pelaksanaan CBT (View Halaman Ujian)
     */
    public function soal($id, $no)
    {
        /** @var \App\Models\CBT\CbtPeserta $sesi */
        // ✅ Cache sesi+ujian di Redis — hemat 1 query DB per request
        $sesi = Cache::remember("cbt_sesi:{$id}", 3600, function () use ($id) {
            return CbtPeserta::with(['ujian'])->find($id);
        });

        if (!$sesi || $sesi->user_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($sesi->status, ['selesai', 'timeout'])) {
            return redirect()->route('siswa.ujian.hasil', $sesi->id);
        }

        // Cek Single Session
        if ($sesi->session_token !== session()->getId()) {
            return redirect()->route('siswa.dashboard')->with('error', 'Sesi ujian Anda tidak valid (mungkin login di perangkat lain).');
        }

        // Load semua jawaban untuk navigasi grid (indexed query, tetap dari DB)
        $semuaJawaban = CbtPesertaJawaban::where('cbt_peserta_id', $sesi->id)
            ->orderBy('urutan')
            ->get(['id', 'urutan', 'cbt_opsi_jawaban_id', 'jawaban_essay', 'ragu_ragu']);

        $totalSoal = $semuaJawaban->count();
        if ($no < 1 || $no > $totalSoal) {
            return redirect()->route('siswa.ujian.soal', [$sesi->id, 1]);
        }

        // Load jawaban saat ini (lightweight, tanpa eager load bankSoal)
        $jawabanSaatIni = CbtPesertaJawaban::where('cbt_peserta_id', $sesi->id)
            ->where('urutan', $no)
            ->firstOrFail();

        // ✅ Cache bank soal di Redis — SHARED antar semua siswa yang ujian sama
        // 50 soal × 1 cache entry = 50 entries total (bukan 200×50)
        $bankSoal = Cache::remember(
            "cbt_banksoal:{$jawabanSaatIni->cbt_bank_soal_id}",
            7200,
            fn () => CbtBankSoal::with('opsiJawabans')->find($jawabanSaatIni->cbt_bank_soal_id)
        );
        $jawabanSaatIni->setRelation('bankSoal', $bankSoal);

        // Hitung sisa waktu
        $durasiDetik = $sesi->ujian->durasi * 60;
        $detikBerlalu = $sesi->waktu_mulai->diffInSeconds(now());
        $sisaWaktu = max(0, $durasiDetik - $detikBerlalu);

        if ($sisaWaktu == 0) {
            return $this->prosesSubmit($sesi, 'timeout');
        }

        return view('siswa.cbt.soal', compact('sesi', 'semuaJawaban', 'jawabanSaatIni', 'no', 'totalSoal', 'sisaWaktu'));
    }

    /**
     * Autosave Jawaban (AJAX POST)
     */
    public function simpanJawaban(Request $request, $id)
    {
        // ✅ Auth check via Redis cache — 0 query DB (sebelumnya 1 query)
        $sesi = Cache::remember("cbt_sesi:{$id}", 3600, function () use ($id) {
            return CbtPeserta::find($id);
        });

        if (!$sesi || $sesi->user_id !== Auth::id() || in_array($sesi->status, ['selesai', 'timeout'])) {
            return response()->json(['status' => 'error'], 403);
        }

        // Validasi waktu server-side (Grace period 15 detik)
        $sesi->loadMissing('ujian');
        $durasiDetik = $sesi->ujian->durasi * 60;
        $detikBerlalu = $sesi->waktu_mulai->diffInSeconds(now());
        if ($detikBerlalu > ($durasiDetik + 15)) {
            // Auto submit jika kedaluwarsa
            app(\App\Services\CbtService::class)->gradeAndSubmit($sesi, 'timeout');
            return response()->json(['status' => 'timeout', 'message' => 'Waktu ujian telah habis.'], 403);
        }

        // ✅ Direct DB update — 1 query (sebelumnya 2 query: load + save)
        $updateData = ['updated_at' => now()];

        if ($request->has('cbt_opsi_jawaban_id')) {
            $updateData['cbt_opsi_jawaban_id'] = $request->cbt_opsi_jawaban_id;
        } elseif ($request->has('jawaban_essay')) {
            $updateData['jawaban_essay'] = $request->jawaban_essay;
        }

        if ($request->has('ragu_ragu')) {
            $updateData['ragu_ragu'] = $request->boolean('ragu_ragu');
        }

        DB::table('cbt_peserta_jawabans')
            ->where('cbt_peserta_id', $id)
            ->where('urutan', $request->urutan)
            ->update($updateData);

        return response()->json(['status' => 'saved', 'saved_at' => now()->format('H:i:s')]);
    }

    /**
     * Submit Ujian (Manual)
     */
    public function submit(Request $request, $id)
    {
        $sesi = Cache::remember("cbt_sesi:{$id}", 3600, fn () => CbtPeserta::find($id));
        if (!$sesi || $sesi->user_id !== Auth::id()) {
            abort(403);
        }

        return $this->prosesSubmit($sesi, 'selesai');
    }

    /**
     * Log Anti-Cheat Blur (AJAX POST)
     */
    public function logBlur($id)
    {
        // ✅ Auth via cached sesi + direct DB increment — 1 query (sebelumnya 2)
        $sesi = Cache::remember("cbt_sesi:{$id}", 3600, fn () => CbtPeserta::find($id));
        if (!$sesi || $sesi->user_id != Auth::id()) {
            return response()->json(['status' => 'error'], 403);
        }

        DB::table('cbt_pesertas')->where('id', $id)->increment('blur_count');

        return response()->json(['status' => 'logged']);
    }

    /**
     * Lihat Hasil / Review
     */
    public function hasil($id)
    {
        $sesi = CbtPeserta::with(['ujian', 'jawabans.bankSoal.opsiJawabans', 'jawabans.opsiJawaban'])
            ->withCount(['jawabans as belum_dikoreksi_count' => function($q) {
                $q->whereNull('is_benar');
            }])
            ->findOrFail($id);

        if ($sesi->user_id !== Auth::id()) {
            abort(403);
        }

        // Lock check (Dihapus sesuai permintaan agar tidak perlu bayar full untuk lihat hasil)
        // $pesertaDidik = Auth::user()->pesertaDidik;
        // if ($pesertaDidik) {
        //     $pembayaran = $pesertaDidik->pembayaran()->first();
        //     if ($pembayaran && !$pembayaran->lunas && !$pembayaran->dispensasi) {
        //         return redirect()->route('siswa.ujian.riwayat')->with('error', '⚠️ Silakan melunasi tagihan Anda untuk melihat hasil & analisis ujian.');
        //     }
        // }

        return view('siswa.cbt.hasil', compact('sesi'));
    }

    // ─── PRIVATE METHODS ──────────────────────────────────────────

    private function prosesSubmit(CbtPeserta $sesi, $status)
    {
        $lockKey = "submit_sesi_{$sesi->id}";
        $lock = Cache::lock($lockKey, 15); // Lock 15 detik selama proses grading
        
        if (!$lock->get()) {
            // Jika ada request ganda di milidetik yang sama, alihkan yang kedua ke hasil
            return redirect()->route('siswa.ujian.hasil', $sesi->id);
        }

        try {
            // ✅ Hapus cache sesi saat ujian selesai
            Cache::forget("cbt_sesi:{$sesi->id}");

            $this->cbtService->gradeAndSubmit($sesi, $status);

            return redirect()->route('siswa.ujian.hasil', $sesi->id)->with('success', 'Ujian telah diselesaikan.');
        } finally {
            $lock->release();
        }
    }

    private function getAssignedUjianIds($userId, $kelompokId): array
    {
        return CbtUjianAssign::where(function($q) use ($userId, $kelompokId) {
            $q->where('tipe_assign', 'user')->where('assign_id', $userId);
            if ($kelompokId) {
                $q->orWhere(function($sq) use ($kelompokId) {
                    $sq->where('tipe_assign', 'kelas')->where('assign_id', $kelompokId);
                });
            }
        })->pluck('cbt_ujian_id')->toArray();
    }

    private function getUjianSelesaiIds($userId, array $assignedUjianIds): array
    {
        $ujianSelesaiIds = CbtPeserta::where('user_id', $userId)
            ->whereIn('status', ['selesai', 'timeout'])
            ->pluck('cbt_ujian_id')
            ->toArray();
            
        $ujianLewatIds = CbtUjian::whereIn('id', $assignedUjianIds)
            ->whereNotNull('waktu_selesai')
            ->where('waktu_selesai', '<', now())
            ->pluck('id')
            ->toArray();

        return array_unique(array_merge($ujianSelesaiIds, $ujianLewatIds));
    }
}


