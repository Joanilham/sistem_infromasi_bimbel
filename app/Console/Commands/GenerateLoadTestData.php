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
    protected $description = 'Generate dummy siswa, masukkan ke kelompok belajar, assign ke ujian aktif, dan buat siswa_data.json untuk K6';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        $this->info("Memulai proses generate {$count} data siswa untuk load test K6...");

        // 1. Cari atau buat Kelompok Belajar khusus Load Test
        $kelompok = KelompokBelajar::firstOrCreate(
            ['nama_kelompok' => 'Kelas Load Test CBT'],
            ['tingkat' => 'SMA', 'is_active' => true]
        );
        $this->info("- Kelompok Belajar 'Kelas Load Test CBT' siap (ID: {$kelompok->id})");

        // 2. Cari ujian CBT yang aktif
        $ujian = CbtUjian::aktif()->first();
        if (!$ujian) {
            $this->error("TIDAK ADA UJIAN AKTIF! Buat minimal 1 ujian CBT yang aktif dari panel Admin terlebih dahulu.");
            return 1;
        }
        $this->info("- Ditemukan Ujian Aktif: '{$ujian->nama_ujian}' (ID: {$ujian->id})");

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
        
        $jsonData = [];

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 1; $i <= $count; $i++) {
            $email = "loadtest{$i}@example.com";
            $nisn = "LDTST" . str_pad($i, 5, '0', STR_PAD_LEFT);

            // Buat Profil Peserta Didik (status otomatis Aktif)
            $peserta = PesertaDidik::updateOrCreate(
                ['nisn' => $nisn],
                [
                    'nama_lengkap' => "Siswa LoadTest {$i}",
                    'jenis_kelamin' => 'L',
                    'asal_sekolah' => 'SMA Load Test',
                    'kelompok_belajar_id' => $kelompok->id,
                    'status' => 'Aktif'
                ]
            );

            // Buat User
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "Siswa LoadTest {$i}",
                    'password' => $hashedPassword,
                    'level' => 'siswa',
                    'peserta_didik_id' => $peserta->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Tambahkan ke Array
            $jsonData[] = [
                'email' => $email,
                'password' => $password,
                'ujian_id' => $ujian->id
            ];
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // 5. Tulis file JSON
        $jsonPath = storage_path('app/siswa_data.json');
        File::put($jsonPath, json_encode($jsonData, JSON_PRETTY_PRINT));

        $this->info("✅ Berhasil generate {$count} data siswa.");
        $this->info("✅ File JSON berhasil dibuat: {$jsonPath}");
        $this->info("Sekarang Anda bisa menjalankan K6 Load Test!");

        return 0;
    }
}
