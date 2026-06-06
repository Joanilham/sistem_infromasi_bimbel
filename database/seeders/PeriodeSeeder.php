<?php

namespace Database\Seeders;

use App\Models\MasterData\Periode;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        $periodes = [
            [
                'tahun_periode' => '2025/2026',
                'is_active'     => true,
            ],
        ];

        foreach ($periodes as $periode) {
            Periode::firstOrCreate(['tahun_periode' => $periode['tahun_periode']], $periode);
        }
    }
}

