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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Keluar'])->default('Aktif')->after('matapelajaran');
            $table->date('tanggal_keluar')->nullable()->after('status');
            $table->text('alasan_keluar')->nullable()->after('tanggal_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'tanggal_keluar', 'alasan_keluar']);
        });
    }
};