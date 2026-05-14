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
        Schema::table('cbt_pesertas', function (Blueprint $table) {
            $table->string('ip_address')->nullable();
            $table->string('session_token')->nullable();
            $table->integer('blur_count')->default(0);
        });

        Schema::table('cbt_peserta_jawabans', function (Blueprint $table) {
            $table->integer('urutan')->nullable();
            $table->json('opsi_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_peserta_jawabans', function (Blueprint $table) {
            $table->dropColumn(['urutan', 'opsi_order']);
        });

        Schema::table('cbt_pesertas', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'session_token', 'blur_count']);
        });
    }
};
