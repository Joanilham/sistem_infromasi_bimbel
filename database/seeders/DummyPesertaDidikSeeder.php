<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;
use Faker\Factory as Faker;

class DummyPesertaDidikSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Pastikan ada rujukan untuk foreign keys
        $pakets = PaketBimbingan::pluck('id')->toArray();
        $kelompoks = KelompokBelajar::pluck('id')->toArray();

        // Ambil ID Kantor dan Periode paling pertama sebagai konteks default untuk dummy data.
        $kantor_default = \App\Models\MasterData\Kantor::first()->id ?? 1;
        $periode_default = \App\Models\MasterData\Periode::first()->id ?? 1;

        if (empty($pakets)) {
            $paket = PaketBimbingan::create([
                'kantor_id' => $kantor_default,
                'periode_id' => $periode_default,
                'nama_paket' => 'Paket SNBT Intensif',
                'nominal' => 3500000
            ]);
            $pakets[] = $paket->id;
        }

        if (empty($kelompoks)) {
            $kel = KelompokBelajar::create([
                'kantor_id' => $kantor_default,
                'periode_id' => $periode_default,
                'nama_kelompok' => 'Kelas Online A'
            ]);
            $kelompoks[] = $kel->id;
        }

        echo "Generating 1000 dummy students...\n";

        for ($i = 1; $i <= 1000; $i++) {
            PesertaDidik::create([
                'kantor_id' => $kantor_default,
                'periode_id' => $periode_default,
                'nama_lengkap' => $faker->name,
                'nisn' => $faker->unique()->numerify('##########'),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->dateTimeBetween('-18 years', '-15 years')->format('Y-m-d'),
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                'alamat_lengkap' => $faker->address,
                'asal_sekolah' => 'SMA Negeri ' . $faker->numberBetween(1, 15) . ' ' . $faker->city,
                'no_telepon' => '08002' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'nama_ayah' => $faker->name('male'),
                'nama_ibu' => $faker->name('female'),
                'pekerjaan_ayah' => $faker->jobTitle,
                'pekerjaan_ibu' => $faker->jobTitle,
                'no_telepon_ayah' => '08003' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'no_telepon_ibu' => '08004' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'informasi_dari' => $faker->randomElement(['Instagram', 'Teman', 'Alumni', 'Brosur', 'Google']),
                'paket_bimbingan_id' => $faker->randomElement($pakets),
                'kelompok_belajar_id' => $faker->randomElement($kelompoks),
                'status' => 'Aktif',
            ]);
        }

        echo "Berhasil membuat 1000 dummy Peserta Didik!\n";
    }
}

