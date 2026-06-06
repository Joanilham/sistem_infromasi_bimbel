<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cbt_bank_soals', function (Blueprint $table) {
            // Kolom pembahasan untuk menjelaskan jawaban benar setelah ujian selesai
            $table->text('pembahasan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cbt_bank_soals', function (Blueprint $table) {
            $table->dropColumn('pembahasan');
        });
    }
};
