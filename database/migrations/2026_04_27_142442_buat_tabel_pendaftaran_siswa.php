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
            $table->timestamp('email_verified_at')->nullable();
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
            $table->foreignId('paket_bimbingan_id')->nullable()->constrained('paket_bimbingans')->onDelete('restrict');
            $table->foreignId('kelompok_belajar_id')->nullable()->constrained('kelompok_belajars')->onDelete('restrict');
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
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->onDelete('restrict');
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('restrict');
            $table->timestamps();
            
            $table->index(['kantor_id', 'periode_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_siswas');
    }
};
