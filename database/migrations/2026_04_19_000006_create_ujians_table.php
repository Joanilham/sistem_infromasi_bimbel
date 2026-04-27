<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bank_soal_id')->nullable()->constrained('bank_soals')->onDelete('set null');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable();
            $table->integer('durasi')->nullable(); // dalam menit
            $table->integer('acak_soal')->default(0);
            $table->integer('tampilkan_hasil')->default(1);
            $table->timestamps();
        });

        Schema::create('ujian_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->foreignId('peserta_didik_id')->constrained('users')->onDelete('cascade');
            $table->integer('skor')->nullable();
            $table->integer('nilai')->nullable();
            $table->text('jawaban')->nullable(); // JSON
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_hasil');
        Schema::dropIfExists('ujians');
    }
};