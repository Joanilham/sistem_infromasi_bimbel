<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('user_id', 'idx_audit_user');
            $table->index('event', 'idx_audit_event');
            $table->index('created_at', 'idx_audit_created');
        });
    }

    /**
     * Kembalikan migration.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('idx_audit_user');
            $table->dropIndex('idx_audit_event');
            $table->dropIndex('idx_audit_created');
        });
    }
};
