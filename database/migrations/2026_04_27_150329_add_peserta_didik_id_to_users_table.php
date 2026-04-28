<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('peserta_didik_id')->nullable()->after('id');
            $table->foreign('peserta_didik_id')->references('id')->on('peserta_didiks')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['peserta_didik_id']);
            $table->dropColumn('peserta_didik_id');
        });
    }
};
