<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambahkan composite index yang kritis untuk performa saat 200+ siswa ujian bersamaan.
 * Index ini mempercepat query paling sering dipanggil selama sesi CBT aktif.
 */
return new class extends Migration
{
    public function up(): void
    {
        // cbt_pesertas — query terberat: dicari setiap kali siswa simpan jawaban
        // Query: WHERE cbt_ujian_id = ? AND user_id = ? AND status = 'mengerjakan'
        Schema::table('cbt_pesertas', function (Blueprint $table) {
            // Composite index untuk query canAttempt() dan ambilSoal()
            if (!$this->hasIndex('cbt_pesertas', 'idx_cbt_peserta_ujian_user_status')) {
                $table->index(['cbt_ujian_id', 'user_id', 'status'], 'idx_cbt_peserta_ujian_user_status');
            }
        });

        // cbt_peserta_jawabans — dicari setiap kali siswa simpan/update jawaban
        // Query: WHERE cbt_peserta_id = ? AND cbt_bank_soal_id = ?
        // Query: WHERE cbt_peserta_id = ? ORDER BY urutan
        Schema::table('cbt_peserta_jawabans', function (Blueprint $table) {
            if (!$this->hasIndex('cbt_peserta_jawabans', 'idx_cbt_pj_peserta_urutan')) {
                $table->index(['cbt_peserta_id', 'urutan'], 'idx_cbt_pj_peserta_urutan');
            }
        });

        // cbt_ujian_soals — dicari saat gradeAndSubmit() untuk ambil bobot
        // Query: WHERE cbt_ujian_id = ? AND cbt_bank_soal_id IN (...)
        Schema::table('cbt_ujian_soals', function (Blueprint $table) {
            if (!$this->hasIndex('cbt_ujian_soals', 'idx_cbt_us_ujian_soal')) {
                $table->index(['cbt_ujian_id', 'cbt_bank_soal_id'], 'idx_cbt_us_ujian_soal');
            }
        });

        // personal_access_tokens — dicari setiap API request (auth:sanctum)
        // Query: WHERE tokenable_id = ? AND tokenable_type = ?
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            if (!$this->hasIndex('personal_access_tokens', 'idx_pat_tokenable')) {
                $table->index(['tokenable_id', 'tokenable_type'], 'idx_pat_tokenable');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cbt_pesertas', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_cbt_peserta_ujian_user_status');
        });

        Schema::table('cbt_peserta_jawabans', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_cbt_pj_peserta_urutan');
        });

        Schema::table('cbt_ujian_soals', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_cbt_us_ujian_soal');
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_pat_tokenable');
        });
    }

    /**
     * Check if index exists — agar tidak error jika dipanggil dua kali.
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $conn = Schema::getConnection();
        $dbName = $conn->getDatabaseName();

        $result = $conn->select("
            SELECT COUNT(*) as count
            FROM information_schema.statistics
            WHERE table_schema = ?
              AND table_name = ?
              AND index_name = ?
        ", [$dbName, $table, $indexName]);

        return ($result[0]->count ?? 0) > 0;
    }
};
