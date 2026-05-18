<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\PesertaDidik;
use App\Models\Kantor;
use App\Models\Periode;
use App\Models\PaketBimbingan;
use App\Models\KelompokBelajar;
use App\Models\Master;
use App\Models\KategoriPemasukan;
use App\Models\KategoriPengeluaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\PembayaranSiswa;
use App\Models\TransaksiPembayaran;
use App\Models\CbtMapel;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\PendaftaranSiswa;
use Carbon\Carbon;

class DummyFullSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();
        $testWaNumber = env('WA_TEST_NUMBER') ?: null;

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

        $kantor = Kantor::first() ?? Kantor::firstOrCreate(
            ['nama_kantor' => 'Pusat Banyuwangi'],
            ['alamat' => 'Jl. Pusat']
        );
        $periode = Periode::where('is_active', true)->first() ?? Periode::firstOrCreate(
            ['tahun_periode' => 'Tahun Ajaran 2026/2027'],
            ['is_active' => true]
        );

        // 2. Paket & Kelompok
        $paket = PaketBimbingan::firstOrCreate(
            ['nama_paket' => 'Reguler SMA Intensif'],
            ['kantor_id' => $kantor->id, 'periode_id' => $periode->id, 'nominal' => 2500000]
        );
        $kelompok = KelompokBelajar::firstOrCreate(
            ['nama_kelompok' => 'Kelas X-A'],
            ['kantor_id' => $kantor->id, 'periode_id' => $periode->id]
        );

        // 3. Guru & Mapel
        echo "Creating Mapel & Guru...\n";
        $mapels = ['Matematika', 'Fisika', 'Biologi', 'Bahasa Inggris', 'Kimia'];
        $mapelIds = [];
        foreach ($mapels as $m) {
            $mapelIds[] = CbtMapel::firstOrCreate(['nama' => $m])->id;
        }

        $admin = User::where('level', 'Super Admin')->first() ?? User::where('level', 'Admin')->first();

        $gurus = [];
        for ($i = 1; $i <= 5; $i++) {
            $gurus[] = User::firstOrCreate(
                ['email' => 'guru' . $i . '@example.com'],
                [
                    'name' => $faker->name,
                    'username' => 'guru' . $i,
                    'level' => 'guru',
                    'status' => 'Aktif',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'alamat' => $faker->address,
                    'matapelajaran' => $faker->randomElement($mapels),
                    'no_telp' => '08005' . str_pad($i, 7, '0', STR_PAD_LEFT),
                    'kantor_id' => $kantor->id,
                    'periode_id' => $periode->id
                ]
            );
        }

        // 4. Jadwal Pelajaran
        echo "Creating Jadwal...\n";
        for ($i = 0; $i < 5; $i++) {
            Jadwal::firstOrCreate(
                ['guru_id' => $gurus[$i]->id, 'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'][$i]],
                [
                    'rombel_id' => $kelompok->id,
                    'mata_pelajaran_id' => $faker->randomElement($mapelIds),
                    'jam_mulai' => '15:00:00',
                    'jam_selesai' => '16:30:00',
                    'ruangan' => 'Ruang ' . ($i + 1),
                    'kantor_id' => $kantor->id,
                    'periode_id' => $periode->id
                ]
            );
        }

        // 5. Siswa & Keuangan Siswa
        echo "Creating Siswa & Tagihan...\n";
        $katPemasukan = KategoriPemasukan::firstOrCreate(['nama' => 'Pembayaran SPP']);
        $katPengeluaran = KategoriPengeluaran::firstOrCreate(['nama' => 'Operasional Kantor']);
        
        $siswas = [];
        for ($i = 1; $i <= 10; $i++) {
            $pd = PesertaDidik::firstOrCreate(
                ['nisn' => '100000000' . $i],
                [
                    'kantor_id' => $kantor->id,
                    'periode_id' => $periode->id,
                    'nama_lengkap' => $faker->name,
                    'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                    'tempat_lahir' => $faker->city,
                    'tanggal_lahir' => $faker->dateTimeBetween('-18 years', '-15 years')->format('Y-m-d'),
                    'agama' => 'Islam',
                    'alamat_lengkap' => $faker->address,
                    'asal_sekolah' => 'SMA Negeri 1 ' . $faker->city,
                    'no_telepon' => $testWaNumber ?: ('080000000' . str_pad($i, 3, '0', STR_PAD_LEFT)),
                    'paket_bimbingan_id' => $paket->id,
                    'kelompok_belajar_id' => $kelompok->id,
                    'status' => 'Aktif',
                ]
            );

            User::firstOrCreate(
                ['email' => 'siswa' . $i . '@example.com'],
                [
                    'name' => $pd->nama_lengkap,
                    'username' => 'siswa' . $i,
                    'level' => 'Siswa',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'peserta_didik_id' => $pd->id,
                    'kantor_id' => $kantor->id,
                    'periode_id' => $periode->id
                ]
            );

            // Buat Tagihan
            $tagihan = PembayaranSiswa::firstOrCreate(
                ['peserta_didik_id' => $pd->id],
                [
                    'biaya_pendaftaran' => 250000,
                    'total_harus_dibayar' => 2750000,
                    'batas_waktu' => $now->copy()->addMonths(1)->format('Y-m-d')
                ]
            );

            // Transaksi Pembayaran Sebagian
            if ($i % 2 == 0) {
                $nominal = 1000000;
                TransaksiPembayaran::firstOrCreate(
                    ['pembayaran_siswa_id' => $tagihan->id, 'no_kwitansi' => 'KW-' . $pd->id . '-' . time()],
                    [
                        'nominal' => $nominal,
                        'tanggal' => $now->format('Y-m-d'),
                        'tipe_pembayaran' => 'TRANSFER',
                        'penerima' => $admin->name ?? 'Admin',
                        'user_id' => $admin->id ?? null
                    ]
                );

                Pemasukan::firstOrCreate(
                    ['keterangan' => 'Cicilan SPP ' . $pd->nama_lengkap],
                    [
                        'tanggal' => $now->format('Y-m-d'),
                        'kategori_id' => $katPemasukan->id,
                        'nominal' => $nominal,
                        'user_id' => $admin->id ?? null
                    ]
                );
            }
        }

        // 6. Pengeluaran Kantor
        echo "Creating Pengeluaran...\n";
        for ($i = 0; $i < 5; $i++) {
            Pengeluaran::firstOrCreate(
                ['keterangan' => 'Beli alat tulis kantor batch ' . $i],
                [
                    'tanggal' => $now->copy()->subDays($i)->format('Y-m-d'),
                    'kategori_id' => $katPengeluaran->id,
                    'nominal' => $faker->numberBetween(50, 500) * 1000,
                    'user_id' => $admin->id ?? null
                ]
            );
        }

        // 7. Pendaftaran Online Baru
        echo "Creating Pendaftaran Baru...\n";
        for ($i = 1; $i <= 3; $i++) {
            PendaftaranSiswa::firstOrCreate(
                ['email' => 'pendaftar' . $i . '@example.com'],
                [
                    'password' => Hash::make('password'),
                    'nama_lengkap' => $faker->name,
                    'jenis_kelamin' => 'L',
                    'asal_sekolah' => 'SMA ' . $faker->city,
                    'no_telepon' => $testWaNumber ?: ('080010000' . str_pad($i, 3, '0', STR_PAD_LEFT)),
                    'paket_bimbingan_id' => $paket->id,
                    'kantor_id' => $kantor->id,
                    'periode_id' => $periode->id,
                    'status' => 'menunggu'
                ]
            );
        }

        // 8. Landing Page Content
        echo "Creating Landing Page Content...\n";
        for ($i = 1; $i <= 3; $i++) {
            Testimonial::firstOrCreate(
                ['nama' => $faker->name],
                [
                    'posisi' => 'Siswa Lulusan ' . $now->year,
                    'ulasan' => 'Bimbel di sini sangat membantu saya masuk PTN impian!',
                    'bintang' => 5,
                    'is_active' => true
                ]
            );

            Faq::firstOrCreate(
                ['pertanyaan' => 'Pertanyaan umum ke-' . $i . '?'],
                [
                    'jawaban' => 'Jawaban untuk pertanyaan umum ini adalah sebagai berikut. Sangat jelas dan membantu.',
                    'urutan' => $i,
                    'is_active' => true
                ]
            );
        }

        echo "DummyFullSeeder completed successfully!\n";
    }
}
