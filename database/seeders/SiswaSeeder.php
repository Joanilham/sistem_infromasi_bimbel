<?php

namespace Database\Seeders;

use App\Models\Akademik\KelompokBelajar;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\PesertaDidik;
use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
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

        // 2. Buat Paket Bimbingan jika belum ada
        $paketUtbk = PaketBimbingan::firstOrCreate(
            ['nama_paket' => 'Reguler Intensif UTBK-SNBT'],
            [
                'kantor_id'        => $kantor->id,
                'periode_id'       => $periode->id,
                'nominal'          => 2500000,
                'harga_coret'      => 3000000,
                'durasi_jumlah'    => 6,
                'durasi_satuan'    => 'Bulan',
                'target_peserta'   => 'Kelas 12 & Alumni SMA/SMK',
                'deskripsi'        => 'Program bimbingan intensif persiapan UTBK-SNBT dengan tryout berkala dan pembahasan soal HOTS.',
                'deskripsi_singkat'=> 'Fokus kelulusan PTN impian dengan modul teruji dan bank soal terlengkap.',
                'benefits'         => "10x Tryout CBT Nasional\nModul Lengkap TPS & Literasi\nKonsultasi Jurusan 1-on-1\nAkses Video Pembahasan",
                'is_featured'      => true,
                'is_popular'       => true,
                'label_populer'    => 'Terlaris',
                'urutan'           => 1,
            ]
        );

        $paketKedinasan = PaketBimbingan::firstOrCreate(
            ['nama_paket' => 'Program Khusus Kedinasan & Akmil/Akpol'],
            [
                'kantor_id'        => $kantor->id,
                'periode_id'       => $periode->id,
                'nominal'          => 3500000,
                'harga_coret'      => 4200000,
                'durasi_jumlah'    => 8,
                'durasi_satuan'    => 'Bulan',
                'target_peserta'   => 'Calon Praja & Taruna Kedinasan',
                'deskripsi'        => 'Persiapan komprehensif SKD CAT BKN (TWK, TIU, TKP) serta tes kesamaptaan jasmani.',
                'deskripsi_singkat'=> 'Kesiapan matang tembus STAN, IPDN, STIS, Poltekip, Akmil, dan Akpol.',
                'benefits'         => "Simulasi CAT BKN Realistis\nLatihan Fisik Bersama Pelatih\nTes Psikologi & Wawancara\nBimbingan Kesehatan",
                'is_featured'      => true,
                'is_popular'       => false,
                'urutan'           => 2,
            ]
        );

        $paketSmp = PaketBimbingan::firstOrCreate(
            ['nama_paket' => 'Reguler SMP Juara Prestasi'],
            [
                'kantor_id'        => $kantor->id,
                'periode_id'       => $periode->id,
                'nominal'          => 1800000,
                'durasi_jumlah'    => 1,
                'durasi_satuan'    => 'Tahun',
                'target_peserta'   => 'Siswa SMP / MTs Kelas 7, 8, 9',
                'deskripsi'        => 'Bimbingan pendampingan belajar sekolah dan persiapan ujian akhir jenjang SMP.',
                'deskripsi_singkat'=> 'Tingkatkan nilai rapor dan prestasi akademik jenjang SMP.',
                'benefits'         => "Pendampingan PR & Tugas Harian\nBedah Soal Asesmen Standar\nKelas Interaktif Nyaman",
                'is_featured'      => false,
                'is_popular'       => false,
                'urutan'           => 3,
            ]
        );

        // 3. Buat Kelompok Belajar (Rombel) jika belum ada
        $kelompok1 = KelompokBelajar::firstOrCreate(
            ['nama_kelompok' => 'Kelas 12-IPA-1'],
            ['kantor_id' => $kantor->id, 'periode_id' => $periode->id]
        );

        $kelompok2 = KelompokBelajar::firstOrCreate(
            ['nama_kelompok' => 'Kelas 12-IPS-1'],
            ['kantor_id' => $kantor->id, 'periode_id' => $periode->id]
        );

        $kelompok3 = KelompokBelajar::firstOrCreate(
            ['nama_kelompok' => 'Kelas Intensif SNBT-A'],
            ['kantor_id' => $kantor->id, 'periode_id' => $periode->id]
        );

        $kelompok4 = KelompokBelajar::firstOrCreate(
            ['nama_kelompok' => 'Kelas Kedinasan Alpha'],
            ['kantor_id' => $kantor->id, 'periode_id' => $periode->id]
        );

        // 4. Data Siswa & Akun Login
        $siswas = [
            [
                'nama'             => 'Aditya Pratama',
                'email'            => 'siswa1@example.com',
                'username'         => 'siswa1',
                'nisn'             => '0081234501',
                'nomor_induk'      => 'NIS-2026-001',
                'jenis_kelamin'    => 'L',
                'tempat_lahir'     => 'Banyuwangi',
                'tanggal_lahir'    => '2008-04-12',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. KH. Wachid Hasyim No. 10, Genteng, Banyuwangi',
                'asal_sekolah'     => 'SMAN 1 Genteng',
                'no_telepon'       => '081234560001',
                'nama_ayah'        => 'Bambang Supriyanto',
                'nama_ibu'         => 'Sri Wahyuni',
                'pekerjaan_ayah'   => 'Wiraswasta',
                'pekerjaan_ibu'    => 'Guru',
                'no_telepon_ayah'  => '081234560091',
                'paket_id'         => $paketUtbk->id,
                'kelompok_id'      => $kelompok1->id,
            ],
            [
                'nama'             => 'Nadia Putri Safitri',
                'email'            => 'siswa2@example.com',
                'username'         => 'siswa2',
                'nisn'             => '0081234502',
                'nomor_induk'      => 'NIS-2026-002',
                'jenis_kelamin'    => 'P',
                'tempat_lahir'     => 'Banyuwangi',
                'tanggal_lahir'    => '2008-08-25',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. Letjen S. Parman No. 28, Sobo, Banyuwangi',
                'asal_sekolah'     => 'SMAN 1 Glagah',
                'no_telepon'       => '081234560002',
                'nama_ayah'        => 'Agus Setiawan',
                'nama_ibu'         => 'Endang Susilowati',
                'pekerjaan_ayah'   => 'PNS',
                'pekerjaan_ibu'    => 'Ibu Rumah Tangga',
                'no_telepon_ayah'  => '081234560092',
                'paket_id'         => $paketUtbk->id,
                'kelompok_id'      => $kelompok1->id,
            ],
            [
                'nama'             => 'Dimas Anggara Wijaya',
                'email'            => 'siswa3@example.com',
                'username'         => 'siswa3',
                'nisn'             => '0081234503',
                'nomor_induk'      => 'NIS-2026-003',
                'jenis_kelamin'    => 'L',
                'tempat_lahir'     => 'Jember',
                'tanggal_lahir'    => '2008-01-19',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. Jenderal Sudirman No. 77, Banyuwangi',
                'asal_sekolah'     => 'SMAN 2 Taruna Bhayangkara',
                'no_telepon'       => '081234560003',
                'nama_ayah'        => 'Hadi Wijaya',
                'nama_ibu'         => 'Retno Wulandari',
                'pekerjaan_ayah'   => 'TNI/Polri',
                'pekerjaan_ibu'    => 'PNS',
                'no_telepon_ayah'  => '081234560093',
                'paket_id'         => $paketKedinasan->id,
                'kelompok_id'      => $kelompok4->id,
            ],
            [
                'nama'             => 'Siti Nurhaliza',
                'email'            => 'siswa4@example.com',
                'username'         => 'siswa4',
                'nisn'             => '0081234504',
                'nomor_induk'      => 'NIS-2026-004',
                'jenis_kelamin'    => 'P',
                'tempat_lahir'     => 'Banyuwangi',
                'tanggal_lahir'    => '2008-11-03',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. Diponegoro No. 15, Rogojampi',
                'asal_sekolah'     => 'SMAN 1 Rogojampi',
                'no_telepon'       => '081234560004',
                'nama_ayah'        => 'Slamet Riyadi',
                'nama_ibu'         => 'Nur Hayati',
                'pekerjaan_ayah'   => 'Karyawan Swasta',
                'pekerjaan_ibu'    => 'Wiraswasta',
                'no_telepon_ayah'  => '081234560094',
                'paket_id'         => $paketUtbk->id,
                'kelompok_id'      => $kelompok2->id,
            ],
            [
                'nama'             => 'Farhan Alamsyah',
                'email'            => 'siswa5@example.com',
                'username'         => 'siswa5',
                'nisn'             => '0081234505',
                'nomor_induk'      => 'NIS-2026-005',
                'jenis_kelamin'    => 'L',
                'tempat_lahir'     => 'Surabaya',
                'tanggal_lahir'    => '2008-06-14',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. Ahmad Yani No. 50, Banyuwangi',
                'asal_sekolah'     => 'SMAN 1 Giri',
                'no_telepon'       => '081234560005',
                'nama_ayah'        => 'Mulyadi',
                'nama_ibu'         => 'Kartini',
                'pekerjaan_ayah'   => 'Dosen',
                'pekerjaan_ibu'    => 'PNS',
                'no_telepon_ayah'  => '081234560095',
                'paket_id'         => $paketUtbk->id,
                'kelompok_id'      => $kelompok3->id,
            ],
            [
                'nama'             => 'Zahra Aulia Ramadhani',
                'email'            => 'siswa6@example.com',
                'username'         => 'siswa6',
                'nisn'             => '0081234506',
                'nomor_induk'      => 'NIS-2026-006',
                'jenis_kelamin'    => 'P',
                'tempat_lahir'     => 'Banyuwangi',
                'tanggal_lahir'    => '2008-09-09',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. MT Haryono No. 12, Kertosari, Banyuwangi',
                'asal_sekolah'     => 'MAN 1 Banyuwangi',
                'no_telepon'       => '081234560006',
                'nama_ayah'        => 'Ahmad Suhada',
                'nama_ibu'         => 'Siti Fatimah',
                'pekerjaan_ayah'   => 'Wiraswasta',
                'pekerjaan_ibu'    => 'Ibu Rumah Tangga',
                'no_telepon_ayah'  => '081234560096',
                'paket_id'         => $paketUtbk->id,
                'kelompok_id'      => $kelompok3->id,
            ],
            [
                'nama'             => 'Kevin Raditya Putra',
                'email'            => 'siswa7@example.com',
                'username'         => 'siswa7',
                'nisn'             => '0081234507',
                'nomor_induk'      => 'NIS-2026-007',
                'jenis_kelamin'    => 'L',
                'tempat_lahir'     => 'Malang',
                'tanggal_lahir'    => '2008-03-30',
                'agama'            => 'Kristen',
                'alamat_lengkap'   => 'Jl. I Gusti Ngurah Rai No. 8, Banyuwangi',
                'asal_sekolah'     => 'SMA Katolik Santo Hikmat',
                'no_telepon'       => '081234560007',
                'nama_ayah'        => 'Robertus Haryanto',
                'nama_ibu'         => 'Maria Magdalena',
                'pekerjaan_ayah'   => 'Dokter',
                'pekerjaan_ibu'    => 'Apoteker',
                'no_telepon_ayah'  => '081234560097',
                'paket_id'         => $paketKedinasan->id,
                'kelompok_id'      => $kelompok4->id,
            ],
            [
                'nama'             => 'Anisa Rahmawati',
                'email'            => 'siswa8@example.com',
                'username'         => 'siswa8',
                'nisn'             => '0081234508',
                'nomor_induk'      => 'NIS-2026-008',
                'jenis_kelamin'    => 'P',
                'tempat_lahir'     => 'Banyuwangi',
                'tanggal_lahir'    => '2008-12-18',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. Raya Situbondo No. 104, Ketapang',
                'asal_sekolah'     => 'SMAN 1 Banyuwangi',
                'no_telepon'       => '081234560008',
                'nama_ayah'        => 'Sugeng Pramono',
                'nama_ibu'         => 'Tri Utami',
                'pekerjaan_ayah'   => 'Wiraswasta',
                'pekerjaan_ibu'    => 'PNS',
                'no_telepon_ayah'  => '081234560098',
                'paket_id'         => $paketUtbk->id,
                'kelompok_id'      => $kelompok2->id,
            ],
            [
                'nama'             => 'Muhammad Rizky Fauzi',
                'email'            => 'siswa9@example.com',
                'username'         => 'siswa9',
                'nisn'             => '0081234509',
                'nomor_induk'      => 'NIS-2026-009',
                'jenis_kelamin'    => 'L',
                'tempat_lahir'     => 'Banyuwangi',
                'tanggal_lahir'    => '2008-07-04',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. Kolonel Sugiono No. 62, Tukangkayu',
                'asal_sekolah'     => 'SMKN 1 Banyuwangi',
                'no_telepon'       => '081234560009',
                'nama_ayah'        => 'Hendra Saputra',
                'nama_ibu'         => 'Dewi Sartika',
                'pekerjaan_ayah'   => 'Karyawan BUMN',
                'pekerjaan_ibu'    => 'Ibu Rumah Tangga',
                'no_telepon_ayah'  => '081234560099',
                'paket_id'         => $paketUtbk->id,
                'kelompok_id'      => $kelompok1->id,
            ],
            [
                'nama'             => 'Tasya Kamila Putri',
                'email'            => 'siswa10@example.com',
                'username'         => 'siswa10',
                'nisn'             => '0081234510',
                'nomor_induk'      => 'NIS-2026-010',
                'jenis_kelamin'    => 'P',
                'tempat_lahir'     => 'Banyuwangi',
                'tanggal_lahir'    => '2010-02-11',
                'agama'            => 'Islam',
                'alamat_lengkap'   => 'Jl. Veteran No. 88, Banyuwangi',
                'asal_sekolah'     => 'SMPN 1 Banyuwangi',
                'no_telepon'       => '081234560010',
                'nama_ayah'        => 'Ir. Hendro Kusumo',
                'nama_ibu'         => 'dr. Ratna Juwita',
                'pekerjaan_ayah'   => 'Konsultan',
                'pekerjaan_ibu'    => 'Dokter',
                'no_telepon_ayah'  => '081234560100',
                'paket_id'         => $paketSmp->id,
                'kelompok_id'      => null,
            ],
        ];

        foreach ($siswas as $item) {
            // 4a. Buat / update data peserta didik
            $peserta = PesertaDidik::updateOrCreate(
                ['nisn' => $item['nisn']],
                [
                    'kantor_id'           => $kantor->id,
                    'periode_id'          => $periode->id,
                    'nama_lengkap'        => $item['nama'],
                    'nomor_induk'         => $item['nomor_induk'],
                    'jenis_kelamin'       => $item['jenis_kelamin'],
                    'tempat_lahir'        => $item['tempat_lahir'],
                    'tanggal_lahir'       => $item['tanggal_lahir'],
                    'agama'               => $item['agama'],
                    'alamat_lengkap'      => $item['alamat_lengkap'],
                    'asal_sekolah'        => $item['asal_sekolah'],
                    'no_telepon'          => $item['no_telepon'],
                    'nama_ayah'           => $item['nama_ayah'],
                    'nama_ibu'            => $item['nama_ibu'],
                    'pekerjaan_ayah'      => $item['pekerjaan_ayah'],
                    'pekerjaan_ibu'       => $item['pekerjaan_ibu'],
                    'no_telepon_ayah'     => $item['no_telepon_ayah'],
                    'paket_bimbingan_id'  => $item['paket_id'],
                    'kelompok_belajar_id' => $item['kelompok_id'],
                    'status'              => 'Aktif',
                ]
            );

            // 4b. Buat / update akun login user untuk siswa
            User::updateOrCreate(
                ['email' => $item['email']],
                [
                    'name'             => $item['nama'],
                    'username'         => $item['username'],
                    'password'         => Hash::make('password123'),
                    'level'            => 'Siswa',
                    'is_active'        => true,
                    'status'           => 'aktif',
                    'peserta_didik_id' => $peserta->id,
                    'kantor_id'        => $kantor->id,
                    'periode_id'       => $periode->id,
                ]
            );
        }
    }
}
