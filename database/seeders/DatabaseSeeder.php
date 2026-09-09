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
            ['alamat' => 'Cluring, Banyuwangi']
        );
        $periode = \App\Models\MasterData\Periode::first();

        \App\Models\MasterData\Master::firstOrCreate(
            ['id' => 1],
            [
                'nama_lembaga'   => 'Nivora',
                'alamat_lembaga' => 'Jl. Adi Sucipto No. 88, Sobo, Kec. Banyuwangi, Kabupaten Banyuwangi, Jawa Timur 68418',
                'email_kontak'   => 'sekretariat@nivora.id',
                'telepon_kantor' => '(0333) 412345',
                'jam_layanan'    => 'Senin – Sabtu (08.00 – 20.00 WIB)',
                'stats_siswa'    => '1,500+',
                'stats_tutor'    => '98.4%',
                'stats_modul'    => '100+',
                'stats_kepuasan' => '4.9/5',
                'logo'           => null,
            ]
        );

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

