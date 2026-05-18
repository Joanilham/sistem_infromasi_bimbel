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
        Schema::create('paket_bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->onDelete('restrict');
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('restrict');
            $table->string('nama_paket');
            $table->decimal('nominal', 12, 0);
            $table->decimal('harga_coret', 12, 0)->nullable();
            $table->integer('durasi_jumlah')->nullable();
            $table->string('durasi_satuan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('deskripsi_singkat')->nullable();
            $table->text('benefits')->nullable();
            $table->string('target_peserta')->nullable();
            $table->text('fasilitas')->nullable();
            $table->string('gambar_paket')->nullable();
            $table->string('kategori')->nullable();
            $table->string('warna_badge')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->string('label_populer')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
            
            $table->index(['kantor_id', 'periode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_bimbingans');
    }
};
