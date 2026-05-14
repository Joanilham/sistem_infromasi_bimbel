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
        Schema::table('users', function (Blueprint $column) {
            $column->foreignId('kantor_id')->nullable()->constrained('kantors')->onDelete('cascade');
            $column->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $column) {
            $column->dropForeign(['kantor_id']);
            $column->dropColumn('kantor_id');
            $column->dropForeign(['periode_id']);
            $column->dropColumn('periode_id');
        });
    }
};
