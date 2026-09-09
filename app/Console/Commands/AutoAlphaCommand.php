<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AutoAlphaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:auto-alpha {--force : Paksa jalankan meskipun pengaturan dimatikan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis menandai siswa yang tidak memiliki data absen hari ini sebagai Alpha.';

    const SETTINGS_FILE = 'absensi_settings.json';

    public static function defaultSettings(): array
    {
        return [
            'auto_alpha_enabled' => true,
            'auto_alpha_time'    => '23:00',
            'exclude_sunday'     => false,
            'last_run_at'        => null,
            'last_run_count'     => 0,
        ];
    }

    public static function getSettings(): array
    {
        if (Storage::disk('local')->exists(self::SETTINGS_FILE)) {
            $data = json_decode(Storage::disk('local')->get(self::SETTINGS_FILE), true);
            if (is_array($data)) {
                return array_merge(self::defaultSettings(), $data);
            }
        }
        return self::defaultSettings();
    }

    public static function isAutoAlphaEnabled(): bool
    {
        $settings = self::getSettings();
        return (bool) ($settings['auto_alpha_enabled'] ?? true);
    }

    public static function saveSettings(array $settings): array
    {
        $current = self::getSettings();
        $updated = array_merge($current, $settings);
        Storage::disk('local')->put(self::SETTINGS_FILE, json_encode($updated, JSON_PRETTY_PRINT));
        return $updated;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan absensi harian...');

        $settings = self::getSettings();
        $isForce = (bool) $this->option('force');

        if (!$isForce && !($settings['auto_alpha_enabled'] ?? true)) {
            $this->warn('Fitur Auto Alpha sedang NONAKTIF.');
            return 0;
        }

        if (!$isForce && ($settings['exclude_sunday'] ?? false) && Carbon::today()->isSunday()) {
            $this->info('Hari ini hari Minggu dan opsi lewati hari Minggu aktif. Pengecekan dibatalkan.');
            return 0;
        }

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

        self::saveSettings([
            'last_run_at'    => now()->format('Y-m-d H:i:s'),
            'last_run_count' => $jumlahDiAlpha,
        ]);

        $this->info("Pengecekan selesai. Sebanyak {$jumlahDiAlpha} siswa ditandai sebagai Alpha.");

        return 0;
    }
}
