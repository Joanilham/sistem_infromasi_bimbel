<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update Masters for Landing Page
        Schema::table('masters', function (Blueprint $blueprint) {
            $blueprint->string('hero_title')->nullable()->after('logo');
            $blueprint->text('hero_subtitle')->nullable()->after('hero_title');
            $blueprint->string('hero_image')->nullable()->after('hero_subtitle');
            $blueprint->integer('hero_overlay_opacity')->default(50)->after('hero_image');
            $blueprint->text('tentang_kami')->nullable()->after('hero_overlay_opacity');
            $blueprint->string('wa_number')->nullable()->after('tentang_kami');
            $blueprint->string('instagram_url')->nullable()->after('wa_number');
            $blueprint->json('landing_sections_visibility')->nullable()->after('instagram_url');
        });

        // Update Paket Bimbingan for Landing Page
        Schema::table('paket_bimbingans', function (Blueprint $blueprint) {
            $blueprint->text('deskripsi')->nullable()->after('nominal');
            $blueprint->string('gambar_paket')->nullable()->after('deskripsi');
            $blueprint->boolean('is_featured')->default(false)->after('gambar_paket');
            $blueprint->integer('urutan')->default(0)->after('is_featured');
        });

        // Create Testimonials Table
        Schema::create('testimonials', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('nama');
            $blueprint->string('posisi')->nullable(); // Misal: Alumni, Orang Tua
            $blueprint->text('ulasan');
            $blueprint->string('foto')->nullable();
            $blueprint->integer('bintang')->default(5);
            $blueprint->boolean('is_active')->default(true);
            $blueprint->timestamps();
        });

        // Create FAQs Table
        Schema::create('faqs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('pertanyaan');
            $blueprint->text('jawaban');
            $blueprint->integer('urutan')->default(0);
            $blueprint->boolean('is_active')->default(true);
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('masters', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'hero_title', 'hero_subtitle', 'hero_image', 
                'hero_overlay_opacity', 'tentang_kami', 'wa_number', 
                'instagram_url', 'landing_sections_visibility'
            ]);
        });

        Schema::table('paket_bimbingans', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['deskripsi', 'gambar_paket', 'is_featured', 'urutan']);
        });

        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('faqs');
    }
};
