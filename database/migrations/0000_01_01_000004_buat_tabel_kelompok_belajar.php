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
        Schema::create('kelompok_belajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->onDelete('restrict');
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('restrict');
            $table->string('nama_kelompok');
            $table->timestamps();
            
            $table->index(['kantor_id', 'periode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_belajars');
    }
};
