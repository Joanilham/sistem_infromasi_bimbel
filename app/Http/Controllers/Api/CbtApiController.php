<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CBT\CbtUjian;
use App\Models\CBT\CbtUjianSoal;
use App\Models\CBT\CbtPeserta;
use App\Models\CBT\CbtPesertaJawaban;
use App\Services\CbtService;
use App\Traits\ApiResponse;

class CbtApiController extends Controller
{
    use ApiResponse;

    public function __construct(protected CbtService $cbtService)
    {}

    public function daftarUjian(Request $request)
    {
        $user = $request->user();

        if (strtolower($user->level) !== 'siswa' && !in_array($user->level, ['Super Admin', 'Admin'])) {
            return $this->errorResponse('Fitur CBT hanya tersedia untuk siswa.', 403);
        }

        $ujianAktif = CbtUjian::aktif()->orderBy('waktu_mulai', 'asc')->get();

        // Tambahkan info status percobaan terakhir user untuk setiap ujian
        $userId = $user->id;
        $ujianAktif->each(function ($ujian) use ($userId) {
            $peserta = CbtPeserta::where('cbt_ujian_id', $ujian->id)
                ->where('user_id', $userId)
                ->latest('created_at')
                ->first();

            // null = belum pernah, 'mengerjakan', 'selesai', 'timeout'
            $ujian->status_peserta = $peserta?->status;
            $ujian->skor_peserta   = $peserta?->skor;
            $ujian->attempt_ke     = $peserta?->attempt_ke ?? 0;
        });

        return $this->successResponse($ujianAktif, 'Daftar ujian berhasil dimuat');
    }


    public function ambilSoal(Request $request, $id)
    {
        $user = $request->user();
        $ujian = CbtUjian::with(['ujianSoals.bankSoal.opsiJawabans'])->findOrFail($id);

        if ($ujian->ujianSoals()->count() === 0) {
            return $this->errorResponse('Ujian belum memiliki soal.', 400);
        }

        // Cek limit attempt (canAttempt ignores 'mengerjakan' sessions if they exist)
        if (!$ujian->canAttempt($user->id)) {
            return $this->errorResponse('Anda telah mencapai batas maksimal percobaan.', 403);
        }

        $peserta = CbtPeserta::where('cbt_ujian_id', $ujian->id)
            ->where('user_id', $user->id)
            ->where('status', 'mengerjakan')
            ->first();

        if (!$peserta) {
            try {
                $peserta = $this->cbtService->initiateSesi($ujian, $user->id, [
                    'session_token' => \Illuminate\Support\Str::random(40),
                    'ip_address'    => $request->ip(),
                ]);
            } catch (\Exception $e) {
                return $this->errorResponse('Gagal memulai ujian: ' . $e->getMessage(), 500);
            }
        }

        $soal = CbtPesertaJawaban::with(['bankSoal' => function($q) {
            // Tidak include pembahasan di sini — hanya saat mengerjakan
            $q->select('id', 'pertanyaan', 'tipe_soal');
        }, 'bankSoal.opsiJawabans' => function($q) {
            $q->select('id', 'cbt_bank_soal_id', 'teks_opsi', 'is_benar');
        }])->where('cbt_peserta_id', $peserta->id)->orderBy('urutan')->get();

        return $this->successResponse([
            'ujian' => $ujian,
            'peserta' => $peserta,
            'soal' => $soal
        ], 'Soal berhasil dimuat');
    }

    public function simpanJawaban(Request $request, $id)
    {
        $request->validate([
            'cbt_bank_soal_id' => 'required|exists:cbt_bank_soals,id',
            'cbt_opsi_jawaban_id' => 'nullable|exists:cbt_opsi_jawabans,id',
            'jawaban_essay' => 'nullable|string',
            'ragu_ragu' => 'boolean'
        ]);

        $user = $request->user();
        $peserta = CbtPeserta::where('cbt_ujian_id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'mengerjakan')
            ->first();

        if (!$peserta) {
            return $this->errorResponse('Ujian sudah selesai atau sesi tidak ditemukan.', 400);
        }

        $jawaban = CbtPesertaJawaban::where('cbt_peserta_id', $peserta->id)
            ->where('cbt_bank_soal_id', $request->cbt_bank_soal_id)
            ->first();

        if (!$jawaban) {
            return $this->errorResponse('Soal tidak ditemukan dalam sesi ini.', 404);
        }

        if ($request->has('cbt_opsi_jawaban_id')) {
            $jawaban->cbt_opsi_jawaban_id = $request->cbt_opsi_jawaban_id;
        } elseif ($request->has('jawaban_essay')) {
            $jawaban->jawaban_essay = $request->jawaban_essay;
        }

        if ($request->has('ragu_ragu')) {
            $jawaban->ragu_ragu = $request->boolean('ragu_ragu');
        }

        $jawaban->save();

        return $this->successResponse($jawaban, 'Jawaban berhasil disimpan');
    }

    public function selesaiUjian(Request $request, $id)
    {
        $user = $request->user();
        $peserta = CbtPeserta::where('cbt_ujian_id', $id)->where('user_id', $user->id)->where('status', 'mengerjakan')->first();

        if (!$peserta) {
            return $this->errorResponse('Sesi ujian tidak ditemukan atau sudah selesai.', 400);
        }

        // Langsung panggil gradeAndSubmit — fungsi ini yang akan set status, waktu_selesai, dan skor
        $sesi = $this->cbtService->gradeAndSubmit($peserta, 'selesai');

        return $this->successResponse([
            'skor'  => (float) $sesi->skor,
            'status'=> $sesi->status,
        ], 'Ujian berhasil diselesaikan');
    }

    /**
     * Ambil hasil ujian yang sudah selesai beserta pembahasan.
     * Digunakan agar siswa bisa melihat kembali hasil ujian kapan saja.
     */
    public function hasilUjian(Request $request, $id)
    {
        $user = $request->user();

        // Ambil sesi terakhir yang sudah selesai
        $peserta = CbtPeserta::where('cbt_ujian_id', $id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'timeout'])
            ->latest('created_at')
            ->first();

        if (!$peserta) {
            return $this->errorResponse('Hasil ujian tidak ditemukan.', 404);
        }

        $soal = CbtPesertaJawaban::with(['bankSoal' => function($q) {
            $q->select('id', 'pertanyaan', 'tipe_soal');
        }, 'bankSoal.opsiJawabans' => function($q) {
            $q->select('id', 'cbt_bank_soal_id', 'teks_opsi', 'is_benar');
        }, 'bankSoal.pembahasan' => function($q) {
            // Ambil pembahasan dari tabel cbt_pembahasans
            $q->select('cbt_bank_soal_id', 'teks_pembahasan', 'referensi');
        }])->where('cbt_peserta_id', $peserta->id)->orderBy('urutan')->get();

        return $this->successResponse([
            'skor'    => $peserta->skor,
            'status'  => $peserta->status,
            'soal'    => $soal,
            'peserta' => $peserta,
        ], 'Hasil ujian berhasil dimuat');
    }
}

