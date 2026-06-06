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
            if (!Schema::hasColumn('masters', 'hero_image')) $table->string('hero_image')->nullable()->after('hero_subtitle');
            if (!Schema::hasColumn('masters', 'hero_cta_text')) $table->string('hero_cta_text')->nullable()->after('hero_image');
            if (!Schema::hasColumn('masters', 'hero_cta_link')) $table->string('hero_cta_link')->nullable()->after('hero_cta_text');
            if (!Schema::hasColumn('masters', 'about_us')) $table->text('about_us')->nullable()->after('hero_cta_link');
            if (!Schema::hasColumn('masters', 'facebook_url')) $table->string('facebook_url')->nullable()->after('about_us');
            if (!Schema::hasColumn('masters', 'instagram_url')) $table->string('instagram_url')->nullable()->after('facebook_url');
            if (!Schema::hasColumn('masters', 'youtube_url')) $table->string('youtube_url')->nullable()->after('instagram_url');
            if (!Schema::hasColumn('masters', 'tiktok_url')) $table->string('tiktok_url')->nullable()->after('youtube_url');
            if (!Schema::hasColumn('masters', 'whatsapp_number')) $table->string('whatsapp_number')->nullable()->after('tiktok_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masters', function (Blueprint $table) {
            // we won't drop it automatically to avoid data loss
        });
    }
};
