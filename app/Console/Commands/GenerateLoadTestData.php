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

        // 2. Cari ujian CBT yang aktif
        $ujian = CbtUjian::where('is_aktif', true)->first();
        if (!$ujian) {
            $this->error("TIDAK ADA UJIAN AKTIF! Buat minimal 1 ujian CBT yang aktif dari panel Admin terlebih dahulu.");
            return 1;
        }
        $this->info("- Ditemukan Ujian Aktif: '{$ujian->nama_ujian}' (ID: {$ujian->id})");

        // 3. Assign Kelompok Belajar ke Ujian tersebut (jika belum)
        CbtUjianAssign::firstOrCreate([
            'cbt_ujian_id' => $ujian->id,
            'assign_type'  => 'kelompok',
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

            // Buat User
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "Siswa LoadTest {$i}",
                    'password' => $hashedPassword,
                    'level' => 'siswa',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Buat Profil Peserta Didik (status otomatis Aktif)
            PesertaDidik::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_lengkap' => "Siswa LoadTest {$i}",
                    'jenis_kelamin' => 'L',
                    'asal_sekolah' => 'SMA Load Test',
                    'kelompok_belajar_id' => $kelompok->id,
                    'status' => 'Aktif'
                ]
            );

            // Tambahkan ke CSV
            $csvData .= "{$email},{$password},{$ujian->id}\n";
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // 5. Tulis file CSV
        $csvPath = base_path('siswa_data.csv');
        File::put($csvPath, $csvData);

        $this->info("✅ Berhasil generate {$count} data siswa.");
        $this->info("✅ File CSV berhasil dibuat: {$csvPath}");
        $this->info("Sekarang Anda bisa menjalankan tes dengan Artillery!");

        return 0;
    }
}
