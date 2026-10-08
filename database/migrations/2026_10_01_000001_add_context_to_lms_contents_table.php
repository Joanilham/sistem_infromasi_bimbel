<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lms_contents', function (Blueprint $table) {
            $table->foreignId('kantor_id')->nullable()->after('id')->constrained('kantors')->nullOnDelete();
            $table->foreignId('periode_id')->nullable()->after('kantor_id')->constrained('periodes')->nullOnDelete();
            $table->index(['kantor_id', 'periode_id']);
        });
    }

    public function down(): void
    {
        Schema::table('lms_contents', function (Blueprint $table) {
            $table->dropForeign(['kantor_id']);
            $table->dropForeign(['periode_id']);
            $table->dropIndex(['kantor_id', 'periode_id']);
            $table->dropColumn(['kantor_id', 'periode_id']);
        });
    }
};
