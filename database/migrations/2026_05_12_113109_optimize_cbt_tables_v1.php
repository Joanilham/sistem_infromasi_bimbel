<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Indexing & Unique Constraint pada Jawaban Peserta
        Schema::table('cbt_peserta_jawabans', function (Blueprint $table) {
            // Mencegah jawaban ganda untuk soal yang sama dalam satu attempt
            // Gunakan index name yang lebih pendek agar tidak error di beberapa DB engine
            $table->unique(['cbt_peserta_id', 'cbt_bank_soal_id'], 'idx_peserta_soal_unique');
            
            // Index untuk mempercepat filter penilaian
            $table->index('is_benar');
            
            // Kolom untuk upload file (future proof)
            if (!Schema::hasColumn('cbt_peserta_jawabans', 'file_jawaban')) {
                $table->string('file_jawaban')->nullable()->after('jawaban_essay');
            }
        });

        // 2. Indexing pada Bank Soal
        Schema::table('cbt_bank_soals', function (Blueprint $table) {
            $table->index('status');
            $table->index('tipe_soal');
            $table->index('deleted_at'); // Optimasi soft deletes
        });

        // 3. Indexing pada Peserta Ujian
        Schema::table('cbt_pesertas', function (Blueprint $table) {
            $table->index('status');
            $table->index(['user_id', 'status']); // Filter ujian saya (siswa)
        });

        // 4. Indexing pada Tabel Ujian
        Schema::table('cbt_ujians', function (Blueprint $table) {
            $table->index('mode');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_ujians', function (Blueprint $table) {
            $table->dropIndex(['mode']);
            $table->dropIndex(['deleted_at']);
        });

        Schema::table('cbt_pesertas', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
        });

        Schema::table('cbt_bank_soals', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['tipe_soal']);
            $table->dropIndex(['deleted_at']);
        });

        Schema::table('cbt_peserta_jawabans', function (Blueprint $table) {
            $table->dropUnique('idx_peserta_soal_unique');
            $table->dropIndex(['is_benar']);
            if (Schema::hasColumn('cbt_peserta_jawabans', 'file_jawaban')) {
                $table->dropColumn('file_jawaban');
            }
        });
    }
};
