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
        Schema::table('pendaftaran_siswas', function (Blueprint $table) {
            $table->string('email_verification_token', 64)->nullable()->after('email_verified_at');
        });
    }

    /**
     * Kembalikan migration.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_siswas', function (Blueprint $table) {
            $table->dropColumn('email_verification_token');
        });
    }
};
