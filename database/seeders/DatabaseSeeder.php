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
        $this->call([
            PeriodeSeeder::class,
        ]);

        $kantor = \App\Models\MasterData\Kantor::firstOrCreate(
            ['nama_kantor' => 'Sistem Akademik Pusat'],
            ['alamat' => 'Cluring']
        );
        $periode = \App\Models\MasterData\Periode::first();

        User::factory()->create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@admin.com',
            'level'    => 'Super Admin',
            'password' => bcrypt('password'),
        ]);

        // Akun Admin dan data dummy dinonaktifkan untuk produksi (cPanel)
        // User::factory()->create([
        //     'name'     => 'Admin',
        //     'email'    => 'admin@admin.com',
        //     ...
        // ]);
        
        // $this->call([
        //     DummyFullSeeder::class,
        // ]);
    }
}

