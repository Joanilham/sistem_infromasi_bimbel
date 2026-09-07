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
        Schema::table('masters', function (Blueprint $table) {
            $table->string('stats_siswa', 50)->nullable()->default('1,500+')->change();
            $table->string('stats_tutor', 50)->nullable()->default('98.4%')->change();
            $table->string('stats_modul', 50)->nullable()->default('100+')->change();
            $table->string('stats_kepuasan', 50)->nullable()->default('4.9/5')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masters', function (Blueprint $table) {
            $table->integer('stats_siswa')->default(0)->change();
            $table->integer('stats_tutor')->default(0)->change();
            $table->integer('stats_modul')->default(0)->change();
            $table->integer('stats_kepuasan')->default(100)->change();
        });
    }
};
