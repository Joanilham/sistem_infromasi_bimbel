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
        Schema::table('cbt_peserta_jawabans', function (Blueprint $table) {
            if (Schema::hasColumn('cbt_peserta_jawabans', 'jawaban_teks')) {
                $table->renameColumn('jawaban_teks', 'jawaban_essay');
            } else {
                $table->longText('jawaban_essay')->nullable()->after('cbt_bank_soal_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_peserta_jawabans', function (Blueprint $table) {
            if (Schema::hasColumn('cbt_peserta_jawabans', 'jawaban_essay')) {
                $table->renameColumn('jawaban_essay', 'jawaban_teks');
            }
        });
    }
};
