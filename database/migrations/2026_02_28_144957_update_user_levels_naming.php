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
        // Update existing records
        DB::table('users')->where('level', 'admin')->update(['level' => 'administrator']);
        DB::table('users')->where('level', 'user')->update(['level' => 'staff']);

        // Update column default
        Schema::table('users', function (Blueprint $table) {
            $table->string('level')->default('administrator')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert records
        DB::table('users')->where('level', 'administrator')->update(['level' => 'admin']);
        DB::table('users')->where('level', 'staff')->update(['level' => 'user']);

        // Revert column default
        Schema::table('users', function (Blueprint $table) {
            $table->string('level')->default('admin')->change();
        });
    }
};
