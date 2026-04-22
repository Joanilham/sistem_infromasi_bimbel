<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah_soal')->default(0);
            $table->timestamps();
        });

        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_soal_id')->constrained('bank_soals')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->string('tipe'); // pilihan_ganda, essay
            $table->json('opsi_a')->nullable();
            $table->json('opsi_b')->nullable();
            $table->json('opsi_c')->nullable();
            $table->json('opsi_d')->nullable();
            $table->string('jawaban_benar')->nullable();
            $table->text('pembahasan')->nullable();
            $table->integer('poin')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soals');
        Schema::dropIfExists('bank_soals');
    }
};