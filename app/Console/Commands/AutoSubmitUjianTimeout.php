<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CBT\CbtPeserta;
use App\Services\CbtService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * AutoSubmitUjianTimeout
 *
 * Mendeteksi peserta ujian yang melewati batas waktu (durasi + 5 menit toleransi)
 * dan melakukan auto-submit otomatis. Dijalankan via scheduler setiap menit.
 *
 * Di Cron/Scheduler server: * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
 */
class AutoSubmitUjianTimeout extends Command
{
    protected $signature = 'cbt:auto-submit-timeout
                            {--dry-run : Preview saja tanpa submit}';

    protected $description = 'Auto-submit ujian CBT yang melewati batas waktu durasi + toleransi 5 menit.';

    public function __construct(protected CbtService $cbtService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $now = Carbon::now();

        // Ambil semua sesi yang masih berstatus 'mengerjakan'
        // dengan relasi ke ujian untuk ambil durasi
        $sesiAktif = CbtPeserta::where('status', 'mengerjakan')
            ->with('ujian:id,durasi,waktu_selesai')
            ->get();

        if ($sesiAktif->isEmpty()) {
            $this->info('Tidak ada sesi ujian yang aktif.');
            return Command::SUCCESS;
        }

        $submitted = 0;
        $toleransiMenit = 5; // Toleransi 5 menit setelah durasi habis

        foreach ($sesiAktif as $sesi) {
            if (!$sesi->ujian) {
                continue;
            }

            // Hitung kapan sesi ini harusnya selesai berdasarkan waktu_mulai + durasi
            $batasSesiHabis = $sesi->waktu_mulai
                ->addMinutes($sesi->ujian->durasi)
                ->addMinutes($toleransiMenit);

            // Juga cek batas ujian global (waktu_selesai di tabel cbt_ujians)
            $ujianSelesaiGlobal = $sesi->ujian->waktu_selesai
                ? Carbon::parse($sesi->ujian->waktu_selesai)->addMinutes($toleransiMenit)
                : null;

            $isTimeout = $now->greaterThan($batasSesiHabis) ||
                ($ujianSelesaiGlobal && $now->greaterThan($ujianSelesaiGlobal));

            if ($isTimeout) {
                if ($isDryRun) {
                    $this->warn("[DRY-RUN] Akan timeout: Sesi ID {$sesi->id} | User ID {$sesi->user_id} | Batas: {$batasSesiHabis}");
                } else {
                    try {
                        $this->cbtService->gradeAndSubmit($sesi, 'timeout');
                        $submitted++;
                        Log::info("[CBT AutoSubmit] Sesi {$sesi->id} (User: {$sesi->user_id}) di-timeout otomatis.", [
                            'cbt_ujian_id' => $sesi->cbt_ujian_id,
                            'waktu_mulai'  => $sesi->waktu_mulai,
                            'batas_habis'  => $batasSesiHabis,
                        ]);
                    } catch (\Throwable $e) {
                        Log::error("[CBT AutoSubmit] Gagal timeout sesi {$sesi->id}: " . $e->getMessage());
                        $this->error("Gagal timeout sesi {$sesi->id}: " . $e->getMessage());
                    }
                }
            }
        }

        if (!$isDryRun) {
            $this->info("Auto-submit selesai. Total di-timeout: {$submitted} sesi.");
        }

        return Command::SUCCESS;
    }
}
