<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rombel_id')->nullable()->constrained('kelompok_belajars')->onDelete('set null');
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained('cbt_mapels')->onDelete('set null');
            $table->string('hari'); // SENIN, SELASA, RABU, KAMIS, JUMAT, SABTU
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan')->nullable();
            $table->foreignId('kantor_id')->constrained('kantors')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
            $table->timestamps();

            $table->index(['guru_id', 'hari', 'periode_id']);
            $table->index(['rombel_id', 'hari', 'periode_id']);
            $table->index(['kantor_id', 'periode_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
