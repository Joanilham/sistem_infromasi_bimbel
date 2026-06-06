<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Akademik\PesertaDidik;
use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;
use App\Models\MasterData\Master;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ensure Master Data exists
        Master::firstOrCreate(
            ['nama_lembaga' => 'Genius Education'],
            [
                'alamat_lembaga' => 'Jl. Pendidikan No. 123, Banyuwangi',
                'wa_url' => 'https://api.whatsapp.com/send',
                'instance_id' => '12345',
                'wa_token' => 'dummy_token',
                'api_key' => 'dummy_key',
            ]
        );

        $kantor = Kantor::first();
        $periode = Periode::first();

        if (!$kantor || !$periode) {
            $this->call([
                KantorSeeder::class,
                PeriodeSeeder::class,
            ]);
            $kantor = Kantor::first();
            $periode = Periode::first();
        }

        // 2. Create Paket & Kelompok if not exists
        $paket = PaketBimbingan::firstOrCreate(
            ['nama_paket' => 'Reguler SMA'],
            [
                'kantor_id' => $kantor->id,
                'periode_id' => $periode->id,
                'nominal' => 2500000
            ]
        );

        $kelompok = KelompokBelajar::firstOrCreate(
            ['nama_kelompok' => 'Kelas X-A'],
            [
                'kantor_id' => $kantor->id,
                'periode_id' => $periode->id,
            ]
        );

        // 3. Create Guru (Teachers)
        echo "Creating 5 dummy teachers...\n";
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name,
                'username' => 'guru' . $i,
                'email' => 'guru' . $i . '@example.com',
                'level' => 'guru',
                'password' => Hash::make('password'),
                'is_active' => true,
                'alamat' => $faker->address,
                'matapelajaran' => $faker->randomElement(['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Biologi']),
                'nip' => $faker->numerify('##################'),
                'no_telp' => $faker->numerify('08##########'),
                'status' => 'Aktif',
            ]);
        }

        // 4. Create Students (Siswa)
        echo "Creating 10 dummy students...\n";
        for ($i = 1; $i <= 10; $i++) {
            $pd = PesertaDidik::create([
                'kantor_id' => $kantor->id,
                'periode_id' => $periode->id,
                'nama_lengkap' => $faker->name,
                'nisn' => $faker->unique()->numerify('##########'),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->dateTimeBetween('-18 years', '-15 years')->format('Y-m-d'),
                'agama' => 'Islam',
                'alamat_lengkap' => $faker->address,
                'asal_sekolah' => 'SMA Negeri 1 ' . $faker->city,
                'no_telepon' => $faker->numerify('08##########'),
                'paket_bimbingan_id' => $paket->id,
                'kelompok_belajar_id' => $kelompok->id,
                'status' => 'Aktif',
            ]);

            User::create([
                'name' => $pd->nama_lengkap,
                'username' => 'siswa' . $i,
                'email' => 'siswa' . $i . '@example.com',
                'level' => 'siswa',
                'status' => ($i === 2) ? 'menunggu' : 'aktif',
                'password' => Hash::make('password'),
                'is_active' => true,
                'peserta_didik_id' => $pd->id,
            ]);
        }

        echo "Dummy Guru and Siswa created successfully!\n";
    }
}

