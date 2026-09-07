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
            if (!Schema::hasColumn('masters', 'hero_image_2')) {
                $table->string('hero_image_2')->nullable()->after('hero_image');
            }
            if (!Schema::hasColumn('masters', 'hero_image_3')) {
                $table->string('hero_image_3')->nullable()->after('hero_image_2');
            }
            if (!Schema::hasColumn('masters', 'hero_images')) {
                $table->json('hero_images')->nullable()->after('hero_image_3');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masters', function (Blueprint $table) {
            if (Schema::hasColumn('masters', 'hero_image_2')) {
                $table->dropColumn('hero_image_2');
            }
            if (Schema::hasColumn('masters', 'hero_image_3')) {
                $table->dropColumn('hero_image_3');
            }
            if (Schema::hasColumn('masters', 'hero_images')) {
                $table->dropColumn('hero_images');
            }
        });
    }
};
