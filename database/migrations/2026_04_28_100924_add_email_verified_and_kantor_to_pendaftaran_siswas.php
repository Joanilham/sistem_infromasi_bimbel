<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_siswas', function (Blueprint $table) {
            // Token untuk verifikasi email — null berarti belum mengirim link verifikasi
            $table->string('email_verification_token')->nullable()->after('email');
            // Waktu email diverifikasi — null berarti belum diverifikasi
            $table->timestamp('email_verified_at')->nullable()->after('email_verification_token');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_siswas', function (Blueprint $table) {
            $table->dropColumn(['email_verification_token', 'email_verified_at']);
        });
    }
};
