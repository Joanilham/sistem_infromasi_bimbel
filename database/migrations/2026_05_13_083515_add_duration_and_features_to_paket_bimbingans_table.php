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
        Schema::table('paket_bimbingans', function (Blueprint $撐) {
            $撐->integer('durasi_jumlah')->nullable()->after('nominal')->comment('Jumlah durasi (misal: 6)');
            $撐->string('durasi_satuan')->nullable()->after('durasi_jumlah')->comment('Satuan durasi (Bulan/Tahun)');
            $撐->text('benefits')->nullable()->after('deskripsi')->comment('Daftar benefit dipisah baris');
            $撐->string('label_populer')->nullable()->after('is_featured');
            $撐->integer('harga_coret')->nullable()->after('nominal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_bimbingans', function (Blueprint $撐) {
            $撐->dropColumn(['durasi_jumlah', 'durasi_satuan', 'benefits', 'label_populer', 'harga_coret']);
        });
    }
};
