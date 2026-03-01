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
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('agama')->nullable()->after('tanggal_lahir');
            $table->text('alamat_lengkap')->nullable()->after('agama');
            $table->string('no_telepon')->nullable()->after('asal_sekolah');
            $table->string('nama_ayah')->nullable()->after('no_telepon');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable()->after('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable()->after('pekerjaan_ayah');
            $table->string('no_telepon_ayah')->nullable()->after('pekerjaan_ibu');
            $table->string('no_telepon_ibu')->nullable()->after('no_telepon_ayah');
            $table->string('informasi_dari')->nullable()->after('no_telepon_ibu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_didiks', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir',
                'agama',
                'alamat_lengkap',
                'no_telepon',
                'nama_ayah',
                'nama_ibu',
                'pekerjaan_ayah',
                'pekerjaan_ibu',
                'no_telepon_ayah',
                'no_telepon_ibu',
                'informasi_dari'
            ]);
        });
    }
};
