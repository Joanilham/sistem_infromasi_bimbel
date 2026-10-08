<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lms_content_id')->constrained('lms_contents')->cascadeOnDelete();
            $table->foreignId('peserta_didik_id')->constrained('peserta_didiks')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->text('catatan_siswa')->nullable();
            $table->timestamp('submitted_at');
            $table->boolean('is_late')->default(false);
            $table->enum('status', ['submitted', 'graded', 'resubmitted'])->default('submitted');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('feedback_guru')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['lms_content_id', 'peserta_didik_id'], 'uk_lms_sub_content_siswa');
            $table->index(['lms_content_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_submissions');
    }
};
