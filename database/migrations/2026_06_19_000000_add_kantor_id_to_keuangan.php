<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->foreignId('kantor_id')->nullable()->after('id')->constrained('kantors')->onDelete('cascade');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->foreignId('kantor_id')->nullable()->after('id')->constrained('kantors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->dropForeign(['kantor_id']);
            $table->dropColumn('kantor_id');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropForeign(['kantor_id']);
            $table->dropColumn('kantor_id');
        });
    }
};
