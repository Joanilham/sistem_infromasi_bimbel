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
            $table->boolean('dispensasi')->default(false)->after('total_harus_dibayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_siswa', function (Blueprint $table) {
            $table->dropColumn('dispensasi');
        });
    }
};
