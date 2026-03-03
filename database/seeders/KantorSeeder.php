<?php

namespace Database\Seeders;

use App\Models\Kantor;
use Illuminate\Database\Seeder;

class KantorSeeder extends Seeder
{
    public function run(): void
    {
        $kantors = [
            [
                'nama_kantor' => 'Genius Education Pusat',
                'alamat'      => 'Cluring',
            ],
        ];

        foreach ($kantors as $kantor) {
            Kantor::firstOrCreate(['nama_kantor' => $kantor['nama_kantor']], $kantor);
        }
    }
}
