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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->enum('level', ['Super Admin', 'Admin', 'Guru', 'Siswa'])->default('Siswa');
            $table->boolean('is_active')->default(true);
            
            // Kolom khusus Guru
            $table->text('alamat')->nullable();
            $table->string('matapelajaran')->nullable();
            $table->string('nip')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('status')->nullable()->default('Aktif');
            $table->date('tanggal_keluar')->nullable();
            $table->text('alasan_keluar')->nullable();
            
            // Relasi
            $table->foreignId('peserta_didik_id')->nullable()->constrained('peserta_didiks')->onDelete('set null');
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->onDelete('set null');
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('set null');
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
