<?php

namespace App\Http\Controllers\Siswa;
use App\Models\Akademik\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\CBT\CbtUjian;
use App\Models\CBT\CbtPeserta;
use App\Models\CBT\CbtPesertaJawaban;
use App\Models\CBT\CbtUjianAssign;
use App\Services\CbtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if ($pesertaDidik) {
            $pembayaran = $pesertaDidik->pembayaran()->first();
            if ($pembayaran && !$pembayaran->lunas && !$pembayaran->dispensasi) {
                $pembayaranBelumLunas = true;
                $kekurangan = $pembayaran->kekurangan;
            }
        }

        $pesertas = CbtPeserta::where('user_id', Auth::id())
            ->with(['ujian'])
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
            ->where('user_id', Auth::id())
            ->where('status', 'mengerjakan')
            ->first();

        if ($sesiAktif) {
            return redirect()->route('siswa.ujian.soal', [$sesiAktif->id, 1]);
        }

        // 2. Cek Limit Attempt
        if (!$ujian->canAttempt(Auth::id())) {
            return redirect()->route('siswa.ujian.index')->with('error', 'Anda telah mencapai batas maksimal percobaan untuk ujian ini.');
        }

        try {
            $sesi = $this->cbtService->initiateSesi($ujian, Auth::id(), [
                'session_token' => session()->getId(),
                'ip_address'    => $request->ip(),
            ]);

            return redirect()->route('siswa.ujian.soal', [$sesi->id, 1]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulai ujian: ' . $e->getMessage());
        }
    }

    /**
     * S3-F2: Pelaksanaan CBT (View Halaman Ujian)
     */
    public function soal($id, $no)
    {
        /** @var \App\Models\CBT\CbtPeserta $sesi */
        $sesi = CbtPeserta::with(['ujian'])->findOrFail($id);

        if ($sesi->user_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($sesi->status, ['selesai', 'timeout'])) {
            return redirect()->route('siswa.ujian.hasil', $sesi->id);
        }

        // Cek Single Session
        if ($sesi->session_token !== session()->getId()) {
            return redirect()->route('siswa.dashboard')->with('error', 'Sesi ujian Anda tidak valid (mungkin login di perangkat lain).');
        }

        // Load semua jawaban untuk navigasi grid
        $semuaJawaban = CbtPesertaJawaban::where('cbt_peserta_id', $sesi->id)
            ->orderBy('urutan')
            ->get(['id', 'urutan', 'cbt_opsi_jawaban_id', 'jawaban_essay', 'ragu_ragu']);

        $totalSoal = $semuaJawaban->count();
        if ($no < 1 || $no > $totalSoal) {
            return redirect()->route('siswa.ujian.soal', [$sesi->id, 1]);
        }

        // Load soal saat ini
        $jawabanSaatIni = CbtPesertaJawaban::where('cbt_peserta_id', $sesi->id)
            ->where('urutan', $no)
            ->with(['bankSoal.opsiJawabans'])
            ->firstOrFail();

        // Hitung sisa waktu
        $durasiDetik = $sesi->ujian->durasi * 60;
        $detikBerlalu = now()->diffInSeconds($sesi->waktu_mulai);
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
        $sesi = CbtPeserta::findOrFail($id);
        if ($sesi->user_id !== Auth::id() || in_array($sesi->status, ['selesai', 'timeout'])) {
            return response()->json(['status' => 'error'], 403);
        }

        $jawaban = CbtPesertaJawaban::where('cbt_peserta_id', $sesi->id)
            ->where('urutan', $request->urutan)
            ->firstOrFail();

        if ($request->has('cbt_opsi_jawaban_id')) {
            $jawaban->cbt_opsi_jawaban_id = $request->cbt_opsi_jawaban_id;
        } elseif ($request->has('jawaban_essay')) {
            $jawaban->jawaban_essay = $request->jawaban_essay;
        }

        if ($request->has('ragu_ragu')) {
            $jawaban->ragu_ragu = $request->boolean('ragu_ragu');
        }

        $jawaban->save();

        return response()->json(['status' => 'saved', 'saved_at' => now()->format('H:i:s')]);
    }

    /**
     * Submit Ujian (Manual)
     */
    public function submit(Request $request, $id)
    {
        $sesi = CbtPeserta::findOrFail($id);
        if ($sesi->user_id !== Auth::id()) {
            abort(403);
        }

        return $this->prosesSubmit($sesi, 'selesai');
    }

    /**
     * Log Anti-Cheat Blur (AJAX POST)
     */
    public function logBlur($id)
    {
        $sesi = CbtPeserta::findOrFail($id);
        if ($sesi->user_id == Auth::id()) {
            $sesi->increment('blur_count');
            return response()->json(['status' => 'logged', 'count' => $sesi->blur_count]);
        }
        return response()->json(['status' => 'error'], 403);
    }

    /**
     * Lihat Hasil / Review
     */
    public function hasil($id)
    {
        $sesi = CbtPeserta::with(['ujian', 'jawabans.bankSoal.opsiJawabans', 'jawabans.opsiJawaban'])
            ->findOrFail($id);

        if ($sesi->user_id !== Auth::id()) {
            abort(403);
        }

        // Lock check
        $pesertaDidik = Auth::user()->pesertaDidik;
        if ($pesertaDidik) {
            $pembayaran = $pesertaDidik->pembayaran()->first();
            if ($pembayaran && !$pembayaran->lunas && !$pembayaran->dispensasi) {
                return redirect()->route('siswa.ujian.riwayat')->with('error', '⚠️ Silakan melunasi tagihan Anda untuk melihat hasil & analisis ujian.');
            }
        }

        return view('siswa.cbt.hasil', compact('sesi'));
    }

    // ─── PRIVATE METHODS ──────────────────────────────────────────

    private function prosesSubmit(CbtPeserta $sesi, $status)
    {
        return $this->cbtService->gradeAndSubmit($sesi, $status)
            ? redirect()->route('siswa.ujian.hasil', $sesi->id)->with('success', 'Ujian telah diselesaikan.')
            : redirect()->route('siswa.ujian.hasil', $sesi->id);
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


