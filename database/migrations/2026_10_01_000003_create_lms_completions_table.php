<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lms_content_id')->constrained('lms_contents')->cascadeOnDelete();
            $table->foreignId('peserta_didik_id')->constrained('peserta_didiks')->cascadeOnDelete();
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->unique(['lms_content_id', 'peserta_didik_id'], 'uk_lms_comp_content_siswa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_completions');
    }
};
