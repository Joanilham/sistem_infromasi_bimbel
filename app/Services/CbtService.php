<?php

namespace App\Services;

use App\Models\CBT\CbtPeserta;
use App\Models\CBT\CbtPesertaJawaban;
use App\Models\CBT\CbtUjian;
use App\Models\CBT\CbtUjianSoal;
use App\Models\CBT\CbtUjianAssign;
use App\Models\CBT\CbtBankSoal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * CbtService
 *
 * Memusatkan logika bisnis CBT (Computer-Based Test) yang sebelumnya tersebar
 * di BankSoalController, UjianController, CbtSiswaController, dan CbtApiController.
 */
class CbtService
{
    // ═══════════════════════════════════════════════════════
    // SESI UJIAN
    // ═══════════════════════════════════════════════════════

    /**
     * Memulai sesi ujian baru untuk seorang siswa.
     * Membuat CbtPeserta dan bulk-insert semua CbtPesertaJawaban sekaligus.
     *
     * @param  CbtUjian $ujian
     * @param  int      $userId
     * @param  array    $extra   Data tambahan (session_token, ip_address, dll)
     * @return CbtPeserta
     */
    public function initiateSesi(CbtUjian $ujian, int $userId, array $extra = []): CbtPeserta
    {
        $ujian->loadMissing('ujianSoals.bankSoal.opsiJawabans');

        $attemptKe = CbtPeserta::where('cbt_ujian_id', $ujian->id)
            ->where('user_id', $userId)
            ->count() + 1;

        return DB::transaction(function () use ($ujian, $userId, $attemptKe, $extra) {
            $sesi = CbtPeserta::create(array_merge([
                'cbt_ujian_id' => $ujian->id,
                'user_id'      => $userId,
                'waktu_mulai'  => now(),
                'status'       => 'mengerjakan',
                'attempt_ke'   => $attemptKe,
                'session_token'=> $extra['session_token'] ?? Str::random(40),
                'ip_address'   => $extra['ip_address'] ?? null,
            ]));

            $ujianSoals = $ujian->ujianSoals;
            if ($ujian->acak_soal) {
                $ujianSoals = $ujianSoals->shuffle();
            }

            $urutan  = 1;
            $now     = now();
            $inserts = [];

            foreach ($ujianSoals as $us) {
                $opsi      = $us->bankSoal->opsiJawabans;
                $urutanOpsi = null;

                if ($ujian->acak_opsi && $us->bankSoal->tipe_soal === 'pg' && $opsi->count() > 0) {
                    $urutanOpsi = $opsi->shuffle()->pluck('id')->toArray();
                } elseif ($us->bankSoal->tipe_soal === 'pg' && $opsi->count() > 0) {
                    $urutanOpsi = $opsi->pluck('id')->toArray();
                }

                $inserts[] = [
                    'cbt_peserta_id'   => $sesi->id,
                    'cbt_bank_soal_id' => $us->bankSoal->id,
                    'urutan'           => $urutan++,
                    'opsi_order'       => $urutanOpsi ? json_encode($urutanOpsi) : null,
                    'ragu_ragu'        => false,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }

            if (!empty($inserts)) {
                CbtPesertaJawaban::insert($inserts);
            }

            return $sesi;
        });
    }

    /**
     * Melakukan grading otomatis (untuk soal PG) dan menutup sesi ujian.
     *
     * @param  CbtPeserta $sesi
     * @param  string     $status  'selesai' | 'timeout'
     * @return CbtPeserta
     */
    public function gradeAndSubmit(CbtPeserta $sesi, string $status = 'selesai'): CbtPeserta
    {
        if (in_array($sesi->status, ['selesai', 'timeout'])) {
            return $sesi;
        }

        $sesi->status        = $status;
        $sesi->waktu_selesai = now();

        $jawabans = $sesi->jawabans()->with(['bankSoal.opsiJawabans'])->get();

        // Pre-load bobot soal — satu query untuk menghindari N+1
        $bankSoalIds  = $jawabans->pluck('cbt_bank_soal_id')->unique()->toArray();
        $ujianSoalsMap = CbtUjianSoal::where('cbt_ujian_id', $sesi->cbt_ujian_id)
            ->whereIn('cbt_bank_soal_id', $bankSoalIds)
            ->get()
            ->keyBy('cbt_bank_soal_id');

        $totalSkor  = 0;
        $totalBobot = 0;

        foreach ($jawabans as $j) {
            $soal        = $j->bankSoal;
            $bobot       = ($ujianSoalsMap->get($soal->id)?->bobot) ?? 1;
            $totalBobot += $bobot;

            if ($soal->tipe_soal === 'pg') {
                $kunci = $soal->opsiJawabans->where('is_benar', true)->first();

                if ($kunci && $j->cbt_opsi_jawaban_id == $kunci->id) {
                    $j->is_benar  = true;
                    $j->skor      = $bobot;
                    $totalSkor   += $bobot;
                } else {
                    $j->is_benar = false;
                    $j->skor     = 0;
                }
                $j->save();
            }
        }

        $sesi->skor = $totalBobot > 0 ? ($totalSkor / $totalBobot) * 100 : 0;
        $sesi->save();

        return $sesi;
    }

    // ═══════════════════════════════════════════════════════
    // MANAJEMEN SOAL DI UJIAN
    // ═══════════════════════════════════════════════════════

    /**
     * Tambahkan soal-soal ke dalam ujian secara bulk (cek duplikat terlebih dahulu).
     *
     * @param  CbtUjian $ujian
     * @param  array    $soalIds
     * @return int  Jumlah soal yang berhasil ditambahkan
     */
    public function addSoals(CbtUjian $ujian, array $soalIds): int
    {
        $existingIds = $ujian->ujianSoals()->pluck('cbt_bank_soal_id')->flip()->toArray();
        $lastUrutan  = $ujian->ujianSoals()->max('urutan') ?? 0;

        $inserts = [];
        $now     = now();

        foreach ($soalIds as $soalId) {
            if (!isset($existingIds[$soalId])) {
                $lastUrutan++;
                $inserts[] = [
                    'cbt_ujian_id'     => $ujian->id,
                    'cbt_bank_soal_id' => $soalId,
                    'bobot'            => 1,
                    'urutan'           => $lastUrutan,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
                $existingIds[$soalId] = true;
            }
        }

        if (!empty($inserts)) {
            CbtUjianSoal::insert($inserts);
        }

        return count($inserts);
    }

    /**
     * Reorder soal dalam ujian di dalam satu transaksi database.
     *
     * @param  CbtUjian $ujian
     * @param  array    $order  Array [ujianSoalId => urutan_baru]
     * @return void
     */
    public function reorderSoals(CbtUjian $ujian, array $order): void
    {
        DB::transaction(function () use ($ujian, $order) {
            foreach ($order as $urutan => $ujianSoalId) {
                CbtUjianSoal::where('id', $ujianSoalId)
                    ->where('cbt_ujian_id', $ujian->id)
                    ->update(['urutan' => $urutan + 1]);
            }
        });
    }

    /**
     * Set peserta ujian (ganti semua assign lama → assign baru) dengan bulk insert.
     *
     * @param  CbtUjian $ujian
     * @param  array    $kelompokIds
     * @param  array    $siswaIds
     * @return void
     */
    public function setAssignees(CbtUjian $ujian, array $kelompokIds, array $siswaIds): void
    {
        $ujian->assigns()->delete();

        $inserts = [];
        $now     = now();

        foreach ($kelompokIds as $kelompokId) {
            $inserts[] = [
                'cbt_ujian_id' => $ujian->id,
                'tipe_assign'  => 'kelas',
                'assign_id'    => $kelompokId,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        foreach ($siswaIds as $siswaId) {
            $inserts[] = [
                'cbt_ujian_id' => $ujian->id,
                'tipe_assign'  => 'user',
                'assign_id'    => $siswaId,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        if (!empty($inserts)) {
            CbtUjianAssign::insert($inserts);
        }
    }

    // ═══════════════════════════════════════════════════════
    // IMPORT SOAL
    // ═══════════════════════════════════════════════════════

    /**
     * Pre-fetch tabel validasi mapel & bab untuk keperluan import soal.
     * Mengembalikan [$mapelSet, $babSet] — keduanya adalah array berisi ID yang valid.
     *
     * @return array{0: array, 1: array}
     */
    public function getImportValidationSets(): array
    {
        $mapelSet = DB::table('cbt_mapels')->pluck('id')->flip()->toArray();
        $babSet   = DB::table('cbt_babs')->pluck('id')->flip()->toArray();

        return [$mapelSet, $babSet];
    }
}

