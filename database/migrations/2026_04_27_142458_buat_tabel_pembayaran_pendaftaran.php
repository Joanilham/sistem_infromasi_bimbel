<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_siswa_id');
            $table->string('metode_pembayaran')->nullable();
            $table->decimal('jumlah', 15, 2)->nullable();
            $table->string('bukti_pembayaran')->nullable(); // path file
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('pendaftaran_siswa_id')
                  ->references('id')->on('pendaftaran_siswas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_pendaftarans');
    }
};
