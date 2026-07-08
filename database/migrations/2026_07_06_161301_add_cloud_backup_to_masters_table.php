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
            $table->string('cloud_backup_provider')->default('local')->nullable()->after('wa_url');
            $table->string('gdrive_client_id')->nullable()->after('cloud_backup_provider');
            $table->string('gdrive_client_secret')->nullable()->after('gdrive_client_id');
            $table->text('gdrive_refresh_token')->nullable()->after('gdrive_client_secret');
            $table->string('gdrive_folder_id')->nullable()->after('gdrive_refresh_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masters', function (Blueprint $table) {
            $table->dropColumn([
                'cloud_backup_provider',
                'gdrive_client_id',
                'gdrive_client_secret',
                'gdrive_refresh_token',
                'gdrive_folder_id'
            ]);
        });
    }
};
