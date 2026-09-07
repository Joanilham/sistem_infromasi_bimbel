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
        Schema::table('masters', function (Blueprint $table) {
            if (!Schema::hasColumn('masters', 'email_kontak')) {
                $table->string('email_kontak')->nullable()->after('alamat_lembaga');
            }
            if (!Schema::hasColumn('masters', 'telepon_kantor')) {
                $table->string('telepon_kantor')->nullable()->after('email_kontak');
            }
            if (!Schema::hasColumn('masters', 'jam_layanan')) {
                $table->string('jam_layanan')->nullable()->after('telepon_kantor');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masters', function (Blueprint $table) {
            if (Schema::hasColumn('masters', 'email_kontak')) {
                $table->dropColumn('email_kontak');
            }
            if (Schema::hasColumn('masters', 'telepon_kantor')) {
                $table->dropColumn('telepon_kantor');
            }
            if (Schema::hasColumn('masters', 'jam_layanan')) {
                $table->dropColumn('jam_layanan');
            }
        });
    }
};
