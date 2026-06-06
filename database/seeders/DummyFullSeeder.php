<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Akademik\PesertaDidik;
use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;
use App\Models\MasterData\Master;
use App\Models\Keuangan\KategoriPemasukan;
use App\Models\Keuangan\KategoriPengeluaran;
use App\Models\Keuangan\Pemasukan;
use App\Models\Keuangan\Pengeluaran;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Models\CBT\CbtMapel;
use App\Models\Akademik\Jadwal;
use App\Models\Akademik\Absensi;
use App\Models\System\Testimonial;
use App\Models\System\Faq;
use App\Models\System\Gallery;
use App\Models\Pendaftaran\PendaftaranSiswa;
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
                    'level' => 'siswa',
                    'status' => ($i === 2) ? 'menunggu' : 'aktif',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'peserta_didik_id' => $pd->id,
                    'kantor_id' => $kantor->id,
                    'periode_id' => $periode->id
                ]
            );

            // Tentukan status keuangan berdasarkan $i
            // 0 = Tunggakan (Jatuh tempo)
            // 1 = Lunas
            // 2 = Belum lunas tapi belum jatuh tempo
            $financialStatus = $i % 3;

            // Buat Tagihan
            $tagihan = PembayaranSiswa::firstOrCreate(
                ['peserta_didik_id' => $pd->id],
                [
                    'biaya_pendaftaran' => 250000,
                    'total_harus_dibayar' => 2750000,
                    'batas_waktu' => ($financialStatus === 0) ? Carbon::now()->subDays(5) : Carbon::now()->addDays(30),
                    'dispensasi' => false,
                ]
            );

            // Transaksi Pembayaran
            $nominal = 0;
            $keterangan = '';
            
            if ($financialStatus === 1) { // Lunas
                $nominal = 2750000;
                $keterangan = 'Pelunasan SPP ' . $pd->nama_lengkap;
            } elseif ($financialStatus === 0 || $financialStatus === 2) { // Baru Cicilan / Tunggakan
                $nominal = 1000000;
                $keterangan = 'Cicilan SPP ' . $pd->nama_lengkap;
            }

            if ($nominal > 0) {
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
                    ['keterangan' => $keterangan],
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

        // 9. Pengumuman / Berita
        echo "Creating Pengumuman/Berita...\n";
        $beritas = [
            [
                'judul' => 'Jadwal Tryout Akbar Nasional 2024',
                'isi' => 'Pemberitahuan kepada seluruh siswa. Tryout Akbar Nasional akan diselenggarakan pada akhir bulan ini. Diharapkan seluruh siswa mempersiapkan diri sebaik mungkin untuk menghadapi tryout ini. Tryout ini sangat penting untuk mengukur kemampuan kalian sebelum ujian sesungguhnya.',
            ],
            [
                'judul' => 'Pendaftaran Gelombang 2 Dibuka',
                'isi' => 'Kabar gembira! Bimbel kami membuka pendaftaran gelombang kedua dengan diskon khusus 20% bagi yang mendaftar sebelum tanggal 15 bulan depan. Segera informasikan kepada teman-teman yang ingin bergabung.',
            ],
            [
                'judul' => 'Pembagian Kelas Intensif SNBT',
                'isi' => 'Untuk kelas intensif SNBT, pembagian jadwal dan ruangan kelas akan diumumkan pada hari Senin minggu depan. Pastikan kalian mengecek aplikasi mobile atau mading kantor.',
            ],
            [
                'judul' => 'Libur Nasional & Libur Hari Raya',
                'isi' => 'Dalam rangka hari raya, seluruh kegiatan belajar mengajar ditiadakan selama 3 hari. Kegiatan akan kembali normal pada hari Kamis. Tetap semangat belajar di rumah ya!',
            ]
        ];

        foreach ($beritas as $berita) {
            \App\Models\System\Pengumuman::firstOrCreate(
                ['judul' => $berita['judul']],
                [
                    'isi' => $berita['isi'],
                    'is_active' => true,
                ]
            );
        }

        echo "DummyFullSeeder completed successfully!\n";
    }
}

