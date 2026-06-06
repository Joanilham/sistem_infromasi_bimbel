<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah dp_persen_minimal ke tabel masters
        if (!Schema::hasColumn('masters', 'dp_persen_minimal')) {
            Schema::table('masters', function (Blueprint $table) {
                $table->unsignedTinyInteger('dp_persen_minimal')->default(10)->after('stats_kepuasan')
                      ->comment('Persentase minimal DP saat pendaftaran (1-100)');
            });
        }

        // Tambah jenis_bayar ke tabel pembayaran_pendaftarans
        if (!Schema::hasColumn('pembayaran_pendaftarans', 'jenis_bayar')) {
            Schema::table('pembayaran_pendaftarans', function (Blueprint $table) {
                $table->string('jenis_bayar')->default('full')->after('jumlah')
                      ->comment('Jenis pembayaran: full atau dp');
            });
        }
    }

    public function down(): void
    {
        Schema::table('masters', function (Blueprint $table) {
            $table->dropColumn('dp_persen_minimal');
        });

        Schema::table('pembayaran_pendaftarans', function (Blueprint $table) {
            $table->dropColumn('jenis_bayar');
        });
    }
};
