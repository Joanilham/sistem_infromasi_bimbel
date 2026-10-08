<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\CBT\CbtMapel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel pivot guru_mata_pelajaran (Many-to-Many antara users dan cbt_mapels)
        Schema::create('guru_mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cbt_mapel_id')->constrained('cbt_mapels')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'cbt_mapel_id'], 'uk_guru_mapel');
        });

        // 2. Migrasikan data guru yang sudah ada ke pivot table secara otomatis
        $gurus = DB::table('users')
            ->where('level', 'Guru')
            ->whereNotNull('matapelajaran')
            ->where('matapelajaran', '!=', '')
            ->get(['id', 'matapelajaran']);

        foreach ($gurus as $guru) {
            // Pisahkan jika ada guru yang sebelumnya memiliki string beberapa mapel dipisah koma/garis miring
            $rawParts = preg_split('/[,\|\/]/', $guru->matapelajaran);
            foreach ($rawParts as $part) {
                $namaMapel = trim($part);
                if (empty($namaMapel)) {
                    continue;
                }

                $mapel = CbtMapel::firstOrCreate(['nama' => $namaMapel]);

                DB::table('guru_mata_pelajaran')->updateOrInsert(
                    [
                        'user_id'      => $guru->id,
                        'cbt_mapel_id' => $mapel->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_mata_pelajaran');
    }
};
