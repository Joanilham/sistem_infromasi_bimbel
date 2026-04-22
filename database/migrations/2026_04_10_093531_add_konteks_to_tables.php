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
        Schema::table('paket_bimbingans', function (Blueprint $table) {
            $table->foreignId('kantor_id')->constrained('kantors')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
        });

        Schema::table('kelompok_belajars', function (Blueprint $table) {
            $table->foreignId('kantor_id')->constrained('kantors')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
        });

        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->foreignId('kantor_id')->constrained('kantors')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_bimbingans', function (Blueprint $table) {
            $table->dropForeign(['kantor_id']);
            $table->dropForeign(['periode_id']);
            $table->dropColumn(['kantor_id', 'periode_id']);
        });

        Schema::table('kelompok_belajars', function (Blueprint $table) {
            $table->dropForeign(['kantor_id']);
            $table->dropForeign(['periode_id']);
            $table->dropColumn(['kantor_id', 'periode_id']);
        });

        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->dropForeign(['kantor_id']);
            $table->dropForeign(['periode_id']);
            $table->dropColumn(['kantor_id', 'periode_id']);
        });
    }
};
