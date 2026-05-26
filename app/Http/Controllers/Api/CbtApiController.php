<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtUjian;
use App\Models\CbtUjianSoal;
use App\Models\CbtPeserta;
use App\Models\CbtPesertaJawaban;
use App\Traits\ApiResponse;

class CbtApiController extends Controller
{
    use ApiResponse;

    public function daftarUjian(Request $request)
    {
        $user = $request->user();

        if (!$user->peserta_didik_id) {
            return $this->errorResponse('Fitur CBT hanya tersedia untuk siswa.', 403);
        }

        $ujianAktif = CbtUjian::aktif()->orderBy('waktu_mulai', 'asc')->get();

        return $this->successResponse($ujianAktif, 'Daftar ujian berhasil dimuat');
    }

    public function ambilSoal(Request $request, $id)
    {
        $user = $request->user();
        $ujian = CbtUjian::findOrFail($id);

        // Check if user is already a participant, if not create session
        $peserta = CbtPeserta::firstOrCreate(
            ['cbt_ujian_id' => $ujian->id, 'user_id' => $user->id],
            [
                'status' => 'mengerjakan',
                'waktu_mulai' => now(),
            ]
        );

        if ($peserta->status === 'selesai') {
            return $this->errorResponse('Anda sudah menyelesaikan ujian ini.', 400);
        }

        $soal = CbtUjianSoal::with(['bankSoal' => function($q) {
            $q->select('id', 'pertanyaan', 'tipe_soal');
        }, 'bankSoal.opsiJawaban' => function($q) {
            $q->select('id', 'cbt_bank_soal_id', 'teks', 'urutan');
        }])->where('cbt_ujian_id', $ujian->id)->orderBy('urutan')->get();

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
        $peserta = CbtPeserta::where('cbt_ujian_id', $id)->where('user_id', $user->id)->firstOrFail();

        if ($peserta->status === 'selesai') {
            return $this->errorResponse('Ujian sudah selesai.', 400);
        }

        $jawaban = CbtPesertaJawaban::updateOrCreate(
            ['cbt_peserta_id' => $peserta->id, 'cbt_bank_soal_id' => $request->cbt_bank_soal_id],
            [
                'cbt_opsi_jawaban_id' => $request->cbt_opsi_jawaban_id,
                'jawaban_essay' => $request->jawaban_essay,
                'ragu_ragu' => $request->ragu_ragu ?? false,
            ]
        );

        return $this->successResponse($jawaban, 'Jawaban berhasil disimpan');
    }

    public function selesaiUjian(Request $request, $id)
    {
        $user = $request->user();
        $peserta = CbtPeserta::where('cbt_ujian_id', $id)->where('user_id', $user->id)->firstOrFail();

        $peserta->status = 'selesai';
        $peserta->waktu_selesai = now();
        $peserta->save();

        return $this->successResponse(null, 'Ujian berhasil diselesaikan');
    }
}
