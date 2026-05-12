<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\CbtUjian;
use App\Models\CbtPeserta;
use App\Models\CbtPesertaJawaban;
use App\Models\CbtUjianAssign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CbtSiswaController extends Controller
{
    /**
     * S3-F1: Daftar Ujian Tersedia
     */
    public function index()
    {
        $user = Auth::user();
        $pesertaDidik = $user->pesertaDidik;
        $kelompokId = $pesertaDidik ? $pesertaDidik->kelompok_belajar_id : null;

        // Cari ID ujian yang di-assign ke siswa ini (personal atau kelas)
        $assignedUjianIds = CbtUjianAssign::where(function($q) use ($user, $kelompokId) {
            $q->where('tipe_assign', 'user')->where('assign_id', $user->id);
            if ($kelompokId) {
                $q->orWhere(function($sq) use ($kelompokId) {
                    $sq->where('tipe_assign', 'kelas')->where('assign_id', $kelompokId);
                });
            }
        })->pluck('cbt_ujian_id')->toArray();

        // S3-F1: Daftar Ujian Aktif
        $ujianAktif = CbtUjian::whereIn('id', $assignedUjianIds)
            ->aktif()
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        // S3-F1: Daftar Ujian Mendatang
        $ujianMendatang = CbtUjian::whereIn('id', $assignedUjianIds)
            ->mendatang()
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        // S3-F1: Ujian Selesai (sudah disubmit siswa ATAU waktu sudah lewat)
        $ujianSelesaiIds = CbtPeserta::where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'timeout'])
            ->pluck('cbt_ujian_id')
            ->toArray();
            
        $ujianLewatIds = CbtUjian::whereIn('id', $assignedUjianIds)
            ->whereNotNull('waktu_selesai')
            ->where('waktu_selesai', '<', now())
            ->pluck('id')
            ->toArray();

        $allSelesaiIds = array_unique(array_merge($ujianSelesaiIds, $ujianLewatIds));

        $ujianSelesai = CbtUjian::whereIn('id', $allSelesaiIds)
            ->orderBy('waktu_selesai', 'desc')
            ->get();

        // Singkirkan ujian aktif/mendatang jika user sudah menyelesaikannya
        $ujianAktif = $ujianAktif->reject(function($ujian) use ($ujianSelesaiIds) {
            return in_array($ujian->id, $ujianSelesaiIds);
        });

        $ujianMendatang = $ujianMendatang->reject(function($ujian) use ($ujianSelesaiIds) {
            return in_array($ujian->id, $ujianSelesaiIds);
        });

        // Ambil data sesi untuk passing ke view
        $sesis = CbtPeserta::where('user_id', $user->id)->get()->keyBy('cbt_ujian_id');

        return view('siswa.cbt.index', compact('ujianAktif', 'ujianMendatang', 'ujianSelesai', 'sesis'));
    }

    /**
     * S4-F2: Riwayat Ujian Siswa
     */
    public function riwayat()
    {
        $pesertas = CbtPeserta::where('user_id', Auth::id())
            ->with(['ujian'])
            ->whereIn('status', ['selesai', 'timeout'])
            ->orderBy('waktu_selesai', 'desc')
            ->get();

        return view('siswa.cbt.riwayat', compact('pesertas'));
    }

    /**
     * Tata Tertib (Pre-exam)
     */
    public function show($id)
    {
        $ujian = CbtUjian::withCount('ujianSoals as soals_count')->findOrFail($id);
        
        // Cek apakah sudah pernah mulai
        $sesi = CbtPeserta::where('cbt_ujian_id', $ujian->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        $sudahSelesai = false;
        $sedangMengerjakan = false;
        $attempt = $sesi;

        if ($sesi) {
            if (in_array($sesi->status, ['selesai', 'timeout'])) {
                $sudahSelesai = true;
            } else {
                $sedangMengerjakan = true;
                // Jika sedang mengerjakan, langsung arahkan ke soal
                return redirect()->route('siswa.ujian.soal', [$sesi->id, 1]);
            }
        }

        return view('siswa.cbt.tata-tertib', [
            'ujian' => $ujian,
            'sudahSelesai' => $sudahSelesai,
            'sedangMengerjakan' => $sedangMengerjakan,
            'attempt' => $attempt
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

        if ($ujian->token) {
            $request->validate(['token' => 'required|string']);
            if ($request->token !== $ujian->token) {
                return back()->with('error', 'Token ujian tidak valid.');
            }
        }

        // Pastikan belum ada sesi
        $sesi = CbtPeserta::where('cbt_ujian_id', $ujian->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($sesi) {
            return redirect()->route('siswa.ujian.soal', [$sesi->id, 1]);
        }

        DB::beginTransaction();
        try {
            // Buat Sesi Peserta
            $sesi = CbtPeserta::create([
                'cbt_ujian_id' => $ujian->id,
                'user_id'      => Auth::id(),
                'waktu_mulai'  => now(),
                'status'       => 'mengerjakan',
                'ip_address'   => $request->ip(),
                'session_token'=> session()->getId(),
            ]);

            // Siapkan Soal (Randomisasi jika diset)
            $ujianSoals = $ujian->ujianSoals;
            if ($ujian->acak_soal) {
                $ujianSoals = $ujianSoals->shuffle();
            }

            $urutan = 1;
            foreach ($ujianSoals as $us) {
                $opsi = $us->bankSoal->opsiJawabans;
                $urutanOpsi = null;
                
                if ($ujian->acak_opsi && $us->bankSoal->tipe_soal === 'pg' && $opsi->count() > 0) {
                    $urutanOpsi = $opsi->shuffle()->pluck('id')->toArray();
                } elseif ($us->bankSoal->tipe_soal === 'pg' && $opsi->count() > 0) {
                    $urutanOpsi = $opsi->pluck('id')->toArray();
                }

                CbtPesertaJawaban::create([
                    'cbt_peserta_id'   => $sesi->id,
                    'cbt_bank_soal_id' => $us->bankSoal->id,
                    'urutan'           => $urutan++,
                    'opsi_order'       => $urutanOpsi,
                    'ragu_ragu'        => false,
                ]);
            }

            DB::commit();
            return redirect()->route('siswa.ujian.soal', [$sesi->id, 1]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memulai ujian: ' . $e->getMessage());
        }
    }

    /**
     * S3-F2: Pelaksanaan CBT (View Halaman Ujian)
     */
    public function soal($id, $no)
    {
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

        return view('siswa.cbt.hasil', compact('sesi'));
    }

    // ─── PRIVATE METHODS ──────────────────────────────────────────

    private function prosesSubmit(CbtPeserta $sesi, $status)
    {
        if (in_array($sesi->status, ['selesai', 'timeout'])) {
            return redirect()->route('siswa.ujian.hasil', $sesi->id);
        }

        $sesi->status = $status;
        $sesi->waktu_selesai = now();

        // Grading Otomatis (PG)
        $jawabans = $sesi->jawabans()->with(['bankSoal.opsiJawabans'])->get();
        $totalSkor = 0;
        $totalBobot = 0;

        foreach ($jawabans as $j) {
            $soal = $j->bankSoal;
            // Ambil bobot dari tabel pivot ujian_soal
            $ujianSoal = \App\Models\CbtUjianSoal::where('cbt_ujian_id', $sesi->cbt_ujian_id)
                ->where('cbt_bank_soal_id', $soal->id)
                ->first();
            
            $bobot = $ujianSoal ? $ujianSoal->bobot : 1;
            $totalBobot += $bobot;

            if ($soal->tipe_soal === 'pg') {
                $kunci = $soal->opsiJawabans->where('is_benar', true)->first();
                if ($kunci && $j->cbt_opsi_jawaban_id == $kunci->id) {
                    $j->is_benar = true;
                    $j->skor = $bobot;
                    $totalSkor += $bobot;
                } else {
                    $j->is_benar = false;
                    $j->skor = 0; // standard (bukan UTBK -1)
                }
                $j->save();
            }
        }

        // Kalkulasi nilai akhir (skala 100)
        if ($totalBobot > 0) {
            $sesi->skor = ($totalSkor / $totalBobot) * 100;
        } else {
            $sesi->skor = 0;
        }
        
        $sesi->save();

        return redirect()->route('siswa.ujian.hasil', $sesi->id)
            ->with('success', 'Ujian telah diselesaikan.');
    }
}
