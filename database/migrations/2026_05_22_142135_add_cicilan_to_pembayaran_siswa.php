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
        Schema::table('pembayaran_siswa', function (Blueprint $table) {
            $table->integer('jumlah_cicilan')->nullable()->after('batas_waktu');
            $table->integer('nominal_per_cicilan')->nullable()->after('jumlah_cicilan');
            $table->date('jatuh_tempo_berikutnya')->nullable()->after('nominal_per_cicilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_siswa', function (Blueprint $table) {
            $table->dropColumn(['jumlah_cicilan', 'nominal_per_cicilan', 'jatuh_tempo_berikutnya']);
        });
    }
};
