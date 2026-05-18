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
        Schema::create('peserta_didiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->onDelete('restrict');
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('restrict');
            $table->string('nama_lengkap');
            $table->string('nisn')->unique()->nullable();
            $table->string('nomor_induk')->unique()->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('asal_sekolah');
            $table->string('no_telepon')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_telepon_ayah')->nullable();
            $table->string('no_telepon_ibu')->nullable();
            $table->string('informasi_dari')->nullable();
            $table->foreignId('paket_bimbingan_id')->nullable()->constrained('paket_bimbingans')->onDelete('restrict');
            $table->foreignId('kelompok_belajar_id')->nullable()->constrained('kelompok_belajars')->onDelete('restrict');
            $table->string('status')->default('Aktif');
            $table->date('tanggal_keluar')->nullable();
            $table->text('alasan_keluar')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['kantor_id', 'periode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_didiks');
    }
};
