<?php

namespace Database\Seeders;

use App\Models\CBT\CbtMapel;
use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan Kantor & Periode aktif tersedia
        $kantor = Kantor::first() ?? Kantor::firstOrCreate(
            ['nama_kantor' => 'Sistem Akademik Pusat'],
            ['alamat' => 'Cluring, Banyuwangi']
        );

        $periode = Periode::where('is_active', true)->first() ?? Periode::first() ?? Periode::firstOrCreate(
            ['tahun_periode' => '2025/2026'],
            ['is_active' => true]
        );

        // 2. Buat Master Mata Pelajaran CBT jika belum ada
        $mapels = [
            'Matematika',
            'Fisika',
            'Kimia',
            'Biologi',
            'Bahasa Inggris',
            'Bahasa Indonesia',
            'Sejarah',
            'Ekonomi',
            'Sosiologi',
            'Geografi',
        ];

        foreach ($mapels as $mapel) {
            CbtMapel::firstOrCreate(['nama' => $mapel]);
        }

        // 3. Daftar data Guru
        $gurus = [
            [
                'name'          => 'Budi Santoso, M.Pd.',
                'email'         => 'guru.matematika@bimbel.com',
                'username'      => 'guru.matematika',
                'matapelajaran' => 'Matematika',
                'nip'           => '198503122010011002',
                'jenis_kelamin' => 'Laki-Laki',
                'no_telp'       => '081234567801',
                'alamat'        => 'Jl. Gajah Mada No. 12, Genteng, Banyuwangi',
            ],
            [
                'name'          => 'Siti Rahmawati, S.Pd.',
                'email'         => 'guru.fisika@bimbel.com',
                'username'      => 'guru.fisika',
                'matapelajaran' => 'Fisika',
                'nip'           => '198807212014022001',
                'jenis_kelamin' => 'Perempuan',
                'no_telp'       => '081234567802',
                'alamat'        => 'Jl. Adi Sucipto No. 45, Banyuwangi',
            ],
            [
                'name'          => 'Ahmad Fauzi, M.Sc.',
                'email'         => 'guru.kimia@bimbel.com',
                'username'      => 'guru.kimia',
                'matapelajaran' => 'Kimia',
                'nip'           => '198211052009011003',
                'jenis_kelamin' => 'Laki-Laki',
                'no_telp'       => '081234567803',
                'alamat'        => 'Jl. Raya Rogojampi No. 88, Rogojampi',
            ],
            [
                'name'          => 'Dian Paramita, S.Pd.',
                'email'         => 'guru.biologi@bimbel.com',
                'username'      => 'guru.biologi',
                'matapelajaran' => 'Biologi',
                'nip'           => '199004152015032004',
                'jenis_kelamin' => 'Perempuan',
                'no_telp'       => '081234567804',
                'alamat'        => 'Jl. Basuki Rahmat No. 23, Banyuwangi',
            ],
            [
                'name'          => 'Rizky Pratama, M.Hum.',
                'email'         => 'guru.inggris@bimbel.com',
                'username'      => 'guru.inggris',
                'matapelajaran' => 'Bahasa Inggris',
                'nip'           => '198709182012011005',
                'jenis_kelamin' => 'Laki-Laki',
                'no_telp'       => '081234567805',
                'alamat'        => 'Jl. Veteran No. 10, Giri, Banyuwangi',
            ],
            [
                'name'          => 'Nurul Hidayah, S.Pd.',
                'email'         => 'guru.indonesia@bimbel.com',
                'username'      => 'guru.indonesia',
                'matapelajaran' => 'Bahasa Indonesia',
                'nip'           => '199106202016042002',
                'jenis_kelamin' => 'Perempuan',
                'no_telp'       => '081234567806',
                'alamat'        => 'Jl. Kepiting No. 7, Sobo, Banyuwangi',
            ],
            [
                'name'          => 'Hendra Gunawan, S.Pd.',
                'email'         => 'guru.sejarah@bimbel.com',
                'username'      => 'guru.sejarah',
                'matapelajaran' => 'Sejarah',
                'nip'           => '198402102008011007',
                'jenis_kelamin' => 'Laki-Laki',
                'no_telp'       => '081234567807',
                'alamat'        => 'Jl. Piere Tendean No. 15, Banyuwangi',
            ],
            [
                'name'          => 'Maya Lestari, S.E., M.M.',
                'email'         => 'guru.ekonomi@bimbel.com',
                'username'      => 'guru.ekonomi',
                'matapelajaran' => 'Ekonomi',
                'nip'           => '198905252014022008',
                'jenis_kelamin' => 'Perempuan',
                'no_telp'       => '081234567808',
                'alamat'        => 'Jl. Dr. Soetomo No. 56, Banyuwangi',
            ],
            [
                'name'          => 'Arif Budiman, S.Sos.',
                'email'         => 'guru.sosiologi@bimbel.com',
                'username'      => 'guru.sosiologi',
                'matapelajaran' => 'Sosiologi',
                'nip'           => '198612302011011009',
                'jenis_kelamin' => 'Laki-Laki',
                'no_telp'       => '081234567809',
                'alamat'        => 'Jl. PB Sudirman No. 90, Kalipuro',
            ],
            [
                'name'          => 'Eko Prasetyo, S.Si.',
                'email'         => 'guru.geografi@bimbel.com',
                'username'      => 'guru.geografi',
                'matapelajaran' => 'Geografi',
                'nip'           => '198308142007011010',
                'jenis_kelamin' => 'Laki-Laki',
                'no_telp'       => '081234567810',
                'alamat'        => 'Jl. Brawijaya No. 34, Banyuwangi',
            ],
        ];

        foreach ($gurus as $guruData) {
            User::updateOrCreate(
                ['email' => $guruData['email']],
                [
                    'name'          => $guruData['name'],
                    'username'      => $guruData['username'],
                    'password'      => Hash::make('password123'),
                    'level'         => 'Guru',
                    'is_active'     => true,
                    'status'        => 'Aktif',
                    'matapelajaran' => $guruData['matapelajaran'],
                    'nip'           => $guruData['nip'],
                    'jenis_kelamin' => $guruData['jenis_kelamin'],
                    'no_telp'       => $guruData['no_telp'],
                    'alamat'        => $guruData['alamat'],
                    'kantor_id'     => $kantor->id,
                    'periode_id'    => $periode->id,
                ]
            );
        }
    }
}
