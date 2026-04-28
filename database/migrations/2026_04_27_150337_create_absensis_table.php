<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_didik_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->enum('status_masuk', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->boolean('wa_masuk_sent')->default(false);
            $table->boolean('wa_pulang_sent')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('peserta_didik_id')->references('id')->on('peserta_didiks')->onDelete('cascade');
            $table->unique(['peserta_didik_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
