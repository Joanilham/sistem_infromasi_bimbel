<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lms_contents', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('guru_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('lms_contents', function (Blueprint $table) {
            $table->dropIndex(['subject']);
            $table->dropColumn('subject');
        });
    }
};
