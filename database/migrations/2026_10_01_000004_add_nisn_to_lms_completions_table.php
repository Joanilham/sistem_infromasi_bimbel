<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lms_completions', function (Blueprint $table) {
            $table->string('nisn', 50)->nullable()->after('peserta_didik_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('lms_completions', function (Blueprint $table) {
            $table->dropColumn('nisn');
        });
    }
};
