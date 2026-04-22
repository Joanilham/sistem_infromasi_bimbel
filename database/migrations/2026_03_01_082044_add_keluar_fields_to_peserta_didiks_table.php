<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->date('tanggal_keluar')->nullable()->after('status');
            $table->text('alasan_keluar')->nullable()->after('tanggal_keluar');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->dropColumn(['tanggal_keluar', 'alasan_keluar']);
        });
    }
};
