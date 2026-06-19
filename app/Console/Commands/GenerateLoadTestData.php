<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\KelompokBelajar;
use App\Models\CBT\CbtUjian;
use App\Models\CBT\CbtUjianAssign;
use Illuminate\Support\Facades\File;

class GenerateLoadTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cbt:generate-loadtest-data {--count=200 : Jumlah siswa yang akan di-generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dummy siswa, masukkan ke kelompok belajar, assign ke ujian aktif, dan buat siswa_data.csv untuk Artillery';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        $this->info("Memulai proses generate {$count} data siswa untuk load test...");

        // 1. Cari atau buat Kelompok Belajar khusus Load Test
        $kelompok = KelompokBelajar::firstOrCreate(
            ['nama_kelompok' => 'Kelas Load Test CBT'],
            ['tingkat' => 'SMA', 'is_active' => true]
        );
        $this->info("- Kelompok Belajar 'Kelas Load Test CBT' siap (ID: {$kelompok->id})");

        // 2. Cari ujian CBT yang aktif, jika tidak ada, buat otomatis 50 soal
        $ujian = CbtUjian::aktif()->first();
        if (!$ujian) {
            $ujian = CbtUjian::first();
            if ($ujian) {
                $ujian->waktu_mulai = now()->subMinutes(10);
                $ujian->waktu_selesai = now()->addDays(1);
                $ujian->save();
                $this->info("- Ujian '{$ujian->judul}' (ID: {$ujian->id}) tidak aktif, mengaktifkannya secara otomatis...");
            } else {
                $this->info("TIDAK ADA UJIAN DI DATABASE! Membangun Ujian Dummy 50 Soal otomatis...");
                
                $ujian = CbtUjian::create([
                    'judul' => 'Ujian Load Test (50 Soal)',
                    'deskripsi' => 'Ujian ini di-generate otomatis untuk keperluan Load Testing.',
                    'durasi' => 120,
                    'waktu_mulai' => now()->subMinutes(10),
                    'waktu_selesai' => now()->addDays(1),
                    'mode' => 'resmi',
                    'acak_soal' => false,
                    'acak_opsi' => false,
                    'limit_attempt' => 1,
                    'tampilkan_hasil' => true,
                ]);

                for ($i = 1; $i <= 50; $i++) {
                    $bankSoal = \App\Models\CBT\CbtBankSoal::create([
                        'tipe_soal' => 'pg',
                        'tingkat_kesulitan' => 'easy',
                        'pertanyaan' => "<p>Ini adalah pertanyaan dummy ke-{$i} untuk load test. Berapakah hasil dari {$i} + {$i}?</p>",
                        'status' => 'published',
                        'versi' => 1,
                    ]);

                    \App\Models\CBT\CbtUjianSoal::create([
                        'cbt_ujian_id' => $ujian->id,
                        'cbt_bank_soal_id' => $bankSoal->id,
                        'bobot' => 2,
                        'urutan' => $i,
                    ]);

                    for ($j = 0; $j < 5; $j++) {
                        $opsi = chr(65 + $j); // A, B, C, D, E
                        \App\Models\CBT\CbtOpsiJawaban::create([
                            'cbt_bank_soal_id' => $bankSoal->id,
                            'teks_opsi' => "<p>Opsi {$opsi} untuk soal {$i}</p>",
                            'is_benar' => $j === 0, // Opsi A selalu benar
                        ]);
                    }
                }
                $this->info("- Berhasil membangun Ujian (ID: {$ujian->id}) dengan 50 soal pilihan ganda.");
            }
        } else {
            $this->info("- Ditemukan Ujian Aktif: '{$ujian->judul}' (ID: {$ujian->id})");
        }

        // 3. Assign Kelompok Belajar ke Ujian tersebut (jika belum)
        CbtUjianAssign::firstOrCreate([
            'cbt_ujian_id' => $ujian->id,
            'tipe_assign'  => 'kelas',
            'assign_id'    => $kelompok->id,
        ]);
        $this->info("- Ujian berhasil di-assign ke Kelompok Belajar.");

        // 4. Generate Users & PesertaDidik
        $password = 'password';
        $hashedPassword = Hash::make($password);
        
        $csvData = "email,password,ujian_id\n";

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 1; $i <= $count; $i++) {
            $email = "loadtest{$i}@example.com";

            // Buat Profil Peserta Didik terlebih dahulu (status otomatis Aktif)
            $peserta = PesertaDidik::updateOrCreate(
                ['nomor_induk' => "LT-{$i}"],
                [
                    'nama_lengkap' => "Siswa LoadTest {$i}",
                    'jenis_kelamin' => 'L',
                    'asal_sekolah' => 'SMA Load Test',
                    'kelompok_belajar_id' => $kelompok->id,
                    'status' => 'Aktif'
                ]
            );

            // Buat User lalu hubungkan dengan profil Peserta Didik yang baru dibuat
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "Siswa LoadTest {$i}",
                    'password' => $hashedPassword,
                    'level' => 'siswa',
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'peserta_didik_id' => $peserta->id,
                ]
            );

            // Tambahkan ke CSV
            $csvData .= "{$email},{$password},{$ujian->id}\n";
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // 5. Tulis file CSV
        $csvPath = storage_path('app/siswa_data.csv');
        File::put($csvPath, $csvData);

        $this->info("✅ Berhasil generate {$count} data siswa.");
        $this->info("✅ File CSV berhasil dibuat: {$csvPath}");
        $this->info("Silakan pindahkan file tersebut ke folder yang sama dengan script Artillery Anda.");

        return 0;
    }
}
