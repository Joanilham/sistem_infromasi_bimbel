<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom FK baru (nullable dulu agar data lama tidak error)
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->foreignId('kelompok_belajar_id')
                ->nullable()
                ->after('kelompok_belajar')
                ->constrained('kelompok_belajars')
                ->onDelete('set null');
        });

        // 2. Migrasi data lama: cocokkan nama_kelompok → id
        DB::statement("
            UPDATE peserta_didiks pd
            JOIN kelompok_belajars kb ON kb.nama_kelompok = pd.kelompok_belajar
            SET pd.kelompok_belajar_id = kb.id
        ");

        // 3. Hapus kolom string lama
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->dropColumn('kelompok_belajar');
        });
    }

    public function down(): void
    {
        // 1. Tambah kembali kolom string
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->string('kelompok_belajar')->nullable()->after('paket_bimbingan_id');
        });

        // 2. Kembalikan data dari relasi
        DB::statement("
            UPDATE peserta_didiks pd
            JOIN kelompok_belajars kb ON kb.id = pd.kelompok_belajar_id
            SET pd.kelompok_belajar = kb.nama_kelompok
        ");

        // 3. Hapus FK dan kolom
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->dropForeign(['kelompok_belajar_id']);
            $table->dropColumn('kelompok_belajar_id');
        });
    }
};
