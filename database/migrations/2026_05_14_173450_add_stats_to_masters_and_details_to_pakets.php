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
        Schema::table('masters', function (Blueprint $table) {
            $table->string('stats_siswa')->default('1.200+')->after('landing_sections_visibility');
            $table->string('stats_tutor')->default('50+')->after('stats_siswa');
            $table->string('stats_modul')->default('100+')->after('stats_tutor');
            $table->string('stats_kepuasan')->default('98%')->after('stats_modul');
        });

        Schema::table('paket_bimbingans', function (Blueprint $table) {
            $table->text('target_peserta')->nullable()->after('benefits');
            $table->text('fasilitas')->nullable()->after('target_peserta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masters', function (Blueprint $table) {
            $table->dropColumn(['stats_siswa', 'stats_tutor', 'stats_modul', 'stats_kepuasan']);
        });

        Schema::table('paket_bimbingans', function (Blueprint $table) {
            $table->dropColumn(['target_peserta', 'fasilitas']);
        });
    }
};
