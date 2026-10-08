<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lms_completions', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable()->after('nisn')->index();
            $table->timestamp('downloaded_at')->nullable()->after('read_at')->index();
            $table->timestamp('completed_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('lms_completions', function (Blueprint $table) {
            $table->dropIndex(['read_at']);
            $table->dropIndex(['downloaded_at']);
            $table->dropColumn(['read_at', 'downloaded_at']);
        });
    }
};
