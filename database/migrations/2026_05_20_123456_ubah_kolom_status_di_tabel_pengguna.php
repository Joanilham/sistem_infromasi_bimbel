<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah tipe kolom status dari enum ke string agar mendukung status 'Keluar' 
            // dan set default 'Aktif' agar konsisten dengan fungsionalitas aplikasi
            $table->string('status')->nullable()->default('Aktif')->change();
        });
    }

    /**
     * Kembalikan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Nonaktif'])->nullable()->change();
        });
    }
};
