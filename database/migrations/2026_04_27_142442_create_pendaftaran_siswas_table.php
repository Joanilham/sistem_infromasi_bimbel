<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_siswas', function (Blueprint $table) {
            $table->id();
            // Akun
            $table->string('email')->unique();
            $table->string('password');
            // Data Pribadi
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('no_telepon')->nullable();
            // Data Akademik
            $table->string('asal_sekolah');
            $table->unsignedBigInteger('paket_bimbingan_id')->nullable();
            $table->unsignedBigInteger('kelompok_belajar_id')->nullable();
            $table->string('informasi_dari')->nullable();
            // Data Orang Tua
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('no_telepon_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_telepon_ibu')->nullable();
            // Status
            $table->enum('status', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->unsignedBigInteger('kantor_id')->nullable();
            $table->unsignedBigInteger('periode_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_siswas');
    }
};
