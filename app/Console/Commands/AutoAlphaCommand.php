<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\Absensi;
use Carbon\Carbon;

class AutoAlphaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:auto-alpha';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis menandai siswa yang tidak memiliki data absen hari ini sebagai Alpha.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan absensi harian...');

        $tanggalIni = Carbon::today()->toDateString();

        // Mengambil semua peserta didik yang statusnya aktif
        $siswaAktif = PesertaDidik::aktif()->get();

        $jumlahDiAlpha = 0;

        foreach ($siswaAktif as $siswa) {
            // Cek apakah siswa sudah punya record absensi hari ini (apapun statusnya)
            $absenAda = Absensi::where('peserta_didik_id', $siswa->id)
                               ->where('tanggal', $tanggalIni)
                               ->exists();

            if (!$absenAda) {
                // Jika belum punya record sama sekali, tandai sebagai Alpha
                Absensi::create([
                    'peserta_didik_id' => $siswa->id,
                    'tanggal'          => $tanggalIni,
                    'status_masuk'     => 'alpha',
                    // Jam masuk/pulang tidak diisi karena Alpha
                    'keterangan'       => 'Otomatis Alpha oleh Sistem',
                ]);
                $jumlahDiAlpha++;
            }
        }

        $this->info("Pengecekan selesai. Sebanyak {$jumlahDiAlpha} siswa ditandai sebagai Alpha.");
    }
}
