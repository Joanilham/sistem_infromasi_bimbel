<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kelompok_belajar_id')->constrained('kelompok_belajars')->restrictOnDelete();
            $table->enum('type', ['materi', 'tugas']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamps();

            $table->index(['kelompok_belajar_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_contents');
    }
};
