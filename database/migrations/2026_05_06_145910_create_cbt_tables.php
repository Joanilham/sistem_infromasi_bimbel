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
        // Mata Pelajaran untuk Bank Soal
        Schema::create('cbt_mapels', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        // Bab / Sub-bab
        Schema::create('cbt_babs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_mapel_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->timestamps();
        });

        // Bank Soal Utama
        Schema::create('cbt_bank_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_mapel_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cbt_bab_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('tipe_soal', ['pg', 'essay', 'multi_correct'])->default('pg');
            $table->enum('tingkat_kesulitan', ['easy', 'medium', 'hard'])->default('medium');
            $table->json('tags')->nullable(); // HotS, UTBK, dll
            $table->longText('pertanyaan');
            $table->string('file_media')->nullable(); // image / audio / video path
            $table->string('tipe_media')->nullable(); // image, audio, video
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('versi')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Opsi Jawaban (untuk PG dan Multi Correct)
        Schema::create('cbt_opsi_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_bank_soal_id')->constrained()->onDelete('cascade');
            $table->longText('teks_opsi');
            $table->string('file_media')->nullable();
            $table->boolean('is_benar')->default(false);
            $table->timestamps();
        });

        // Pembahasan Detail
        Schema::create('cbt_pembahasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_bank_soal_id')->constrained()->onDelete('cascade');
            $table->longText('teks_pembahasan');
            $table->string('referensi')->nullable();
            $table->timestamps();
        });

        // Manajemen Ujian
        Schema::create('cbt_ujians', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->integer('durasi'); // dalam menit
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->enum('mode', ['latihan', 'resmi'])->default('latihan');
            $table->boolean('acak_soal')->default(false);
            $table->boolean('acak_opsi')->default(false);
            $table->integer('limit_attempt')->default(1); // 0 = unlimited
            $table->string('token')->nullable(); // Anti cheat basic
            $table->boolean('tampilkan_hasil')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Assign Ujian ke Kelas atau User Tertentu
        Schema::create('cbt_ujian_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_ujian_id')->constrained()->onDelete('cascade');
            $table->enum('tipe_assign', ['kelas', 'user']);
            $table->unsignedBigInteger('assign_id'); // bisa ID kelas, kelompok belajar, atau user ID
            $table->timestamps();
        });

        // Mapping Soal ke Ujian
        Schema::create('cbt_ujian_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_ujian_id')->constrained()->onDelete('cascade');
            $table->foreignId('cbt_bank_soal_id')->constrained()->onDelete('cascade');
            $table->integer('bobot')->default(1);
            $table->integer('urutan')->nullable();
            $table->timestamps();
        });

        // Pelaksanaan Ujian (Peserta)
        Schema::create('cbt_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_ujian_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siswa / Peserta
            $table->enum('status', ['mengerjakan', 'selesai', 'timeout'])->default('mengerjakan');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->decimal('skor', 8, 2)->nullable();
            $table->integer('attempt_ke')->default(1);
            $table->timestamps();
        });

        // Jawaban Peserta
        Schema::create('cbt_peserta_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_peserta_id')->constrained()->onDelete('cascade');
            $table->foreignId('cbt_bank_soal_id')->constrained()->onDelete('cascade');
            $table->longText('jawaban_teks')->nullable(); // Untuk essay
            $table->foreignId('cbt_opsi_jawaban_id')->nullable()->constrained()->onDelete('set null'); // Untuk PG
            $table->json('jawaban_multi')->nullable(); // Untuk Multi Correct
            $table->boolean('ragu_ragu')->default(false);
            $table->boolean('is_benar')->nullable(); // Null = belum dinilai (essay)
            $table->decimal('skor', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_peserta_jawabans');
        Schema::dropIfExists('cbt_pesertas');
        Schema::dropIfExists('cbt_ujian_soals');
        Schema::dropIfExists('cbt_ujian_assigns');
        Schema::dropIfExists('cbt_ujians');
        Schema::dropIfExists('cbt_pembahasans');
        Schema::dropIfExists('cbt_opsi_jawabans');
        Schema::dropIfExists('cbt_bank_soals');
        Schema::dropIfExists('cbt_babs');
        Schema::dropIfExists('cbt_mapels');
    }
};
