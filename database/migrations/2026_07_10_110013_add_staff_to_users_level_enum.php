<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN level ENUM('Super Admin', 'Admin', 'Staff', 'Guru', 'Siswa') DEFAULT 'Siswa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this might cause data loss if there are 'Staff' users, but here is the reverse
        DB::statement("ALTER TABLE users MODIFY COLUMN level ENUM('Super Admin', 'Admin', 'Guru', 'Siswa') DEFAULT 'Siswa'");
    }
};
