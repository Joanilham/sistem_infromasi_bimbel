<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PruneAuditLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:prune {--days=30 : Jumlah hari penyimpanan log audit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bersihkan log audit lama yang sudah melewati batas retensi penyimpanan.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $dateLimit = now()->subDays($days);

        $this->info("Memulai pembersihan log audit yang lebih tua dari {$days} hari (sebelum {$dateLimit->toDateString()})...");

        $deletedCount = \App\Models\System\AuditLog::where('created_at', '<', $dateLimit)->delete();

        $this->info("Pembersihan selesai! Berhasil menghapus {$deletedCount} log audit usang.");
    }
}

