<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed kantor & periode terlebih dahulu
        $this->call([
            KantorSeeder::class,
            PeriodeSeeder::class,
            DummyFullSeeder::class,
        ]);

        $kantor = \App\Models\MasterData\Kantor::first();
        $periode = \App\Models\MasterData\Periode::first();

        User::factory()->create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@admin.com',
            'level'    => 'Super Admin',
            'password' => bcrypt('password'),
        ]);
        User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'level'    => 'Admin',
            'password' => bcrypt('password'),
            'kantor_id' => $kantor ? $kantor->id : null,
            'periode_id' => $periode ? $periode->id : null,
        ]);
    }
}

