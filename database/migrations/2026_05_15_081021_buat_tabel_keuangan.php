<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Kategori Pemasukan ────────────────────────────────────
        Schema::create('kategori_pemasukan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
        });

        // ── Pemasukan ─────────────────────────────────────────────
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kategori_id')->constrained('kategori_pemasukan')->onDelete('restrict');
            $table->decimal('nominal', 12, 0);
            $table->string('keterangan', 255)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tanggal']);
        });

        // ── Kategori Pengeluaran ──────────────────────────────────
        Schema::create('kategori_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
        });

        // ── Pengeluaran ───────────────────────────────────────────
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kategori_id')->constrained('kategori_pengeluaran')->onDelete('restrict');
            $table->decimal('nominal', 12, 0);
            $table->string('keterangan', 255)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tanggal']);
        });

        // ── Pembayaran Siswa (Header) ─────────────────────────────
        Schema::create('pembayaran_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_didik_id')->constrained('peserta_didiks')->onDelete('restrict');
            $table->decimal('diskon_persen', 5, 2)->default(0);
            $table->decimal('diskon_nominal', 12, 0)->default(0);
            $table->string('keterangan_diskon', 255)->nullable();
            $table->decimal('biaya_pendaftaran', 12, 0)->default(0);
            $table->decimal('total_harus_dibayar', 12, 0)->default(0);
            $table->date('batas_waktu')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['peserta_didik_id']);
        });

        // ── Transaksi Pembayaran (Detail) ─────────────────────────
        Schema::create('transaksi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_siswa_id')->constrained('pembayaran_siswa')->onDelete('cascade');
            $table->decimal('nominal', 12, 0);
            $table->date('tanggal');
            $table->enum('tipe_pembayaran', ['TUNAI', 'TRANSFER'])->default('TUNAI');
            $table->string('no_kwitansi', 30)->unique()->nullable();
            $table->string('penerima', 100)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tanggal']);
            $table->index(['pembayaran_siswa_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayaran');
        Schema::dropIfExists('pembayaran_siswa');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('kategori_pengeluaran');
        Schema::dropIfExists('pemasukan');
        Schema::dropIfExists('kategori_pemasukan');
    }
};
