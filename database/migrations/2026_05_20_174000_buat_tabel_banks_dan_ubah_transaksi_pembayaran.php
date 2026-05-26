<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel banks
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('atas_nama');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tambah kolom ke transaksi_pembayaran
        Schema::table('transaksi_pembayaran', function (Blueprint $table) {
            $table->string('status', 20)->default('SUKSES'); // PENDING, SUKSES, BATAL
            $table->string('bukti_pembayaran')->nullable();
            $table->text('catatan_siswa')->nullable();
            $table->foreignId('bank_tujuan_id')->nullable()->constrained('banks')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_pembayaran', function (Blueprint $table) {
            $table->dropForeign(['bank_tujuan_id']);
            $table->dropColumn(['status', 'bukti_pembayaran', 'catatan_siswa', 'bank_tujuan_id']);
        });

        Schema::dropIfExists('banks');
    }
};
