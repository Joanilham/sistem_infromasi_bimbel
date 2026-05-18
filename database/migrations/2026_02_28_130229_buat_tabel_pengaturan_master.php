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
        Schema::create('masters', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lembaga')->nullable();
            $table->text('alamat_lembaga')->nullable();
            $table->string('wa_url')->nullable();
            $table->string('instance_id')->nullable();
            $table->string('wa_token')->nullable();
            $table->string('api_key')->nullable();
            $table->string('logo')->nullable();
            
            // Landing Page Fields
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->decimal('hero_overlay_opacity', 3, 2)->default(0.5);
            $table->text('tentang_kami')->nullable();
            $table->string('wa_number')->nullable();
            $table->boolean('wa_widget_status')->default(true);
            $table->string('wa_widget_message')->nullable();
            $table->string('instagram_url')->nullable();
            $table->json('landing_sections_visibility')->nullable();
            
            // Stats
            $table->integer('stats_siswa')->default(0);
            $table->integer('stats_tutor')->default(0);
            $table->integer('stats_modul')->default(0);
            $table->integer('stats_kepuasan')->default(100);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masters');
    }
};
