<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan index performa pada tabel peserta_didiks.
     * Kolom 'status' dan 'nama_lengkap' sering digunakan di WHERE dan ORDER BY.
     */
    public function up(): void
    {
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->index('status');
            $table->index('nama_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['nama_lengkap']);
        });
    }
};
