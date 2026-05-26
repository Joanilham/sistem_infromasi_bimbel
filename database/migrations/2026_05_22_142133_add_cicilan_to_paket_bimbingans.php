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
        Schema::table('paket_bimbingans', function (Blueprint $table) {
            $table->boolean('bisa_dicicil')->default(true)->after('nominal');
            $table->integer('max_cicilan')->default(1)->after('bisa_dicicil')->comment('Max cicilan misal 6 kali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_bimbingans', function (Blueprint $table) {
            $table->dropColumn(['bisa_dicicil', 'max_cicilan']);
        });
    }
};
