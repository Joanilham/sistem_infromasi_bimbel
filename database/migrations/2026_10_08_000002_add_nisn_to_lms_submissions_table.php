<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('lms_submissions', 'nisn')) {
            Schema::table('lms_submissions', function (Blueprint $table) {
                $table->string('nisn', 50)->nullable()->after('peserta_didik_id')->index();
            });

            // Backfill nisn dari peserta_didiks jika data sudah ada
            try {
                DB::statement("
                    UPDATE lms_submissions s 
                    JOIN peserta_didiks p ON s.peserta_didik_id = p.id 
                    SET s.nisn = p.nisn 
                    WHERE s.nisn IS NULL AND p.nisn IS NOT NULL
                ");
            } catch (\Throwable $e) {
                // Ignore if table is empty or error
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lms_submissions', 'nisn')) {
            Schema::table('lms_submissions', function (Blueprint $table) {
                $table->dropIndex(['nisn']);
                $table->dropColumn('nisn');
            });
        }
    }
};
