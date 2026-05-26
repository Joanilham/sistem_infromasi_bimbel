<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paket_bimbingans', function (Blueprint $table) {
            $table->integer('dp_persen_minimal')->default(10)->after('nominal');
        });
    }

    public function down(): void
    {
        Schema::table('paket_bimbingans', function (Blueprint $table) {
            $table->dropColumn('dp_persen_minimal');
        });
    }
};