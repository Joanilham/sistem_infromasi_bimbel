<?php

namespace App\Console\Commands;

use App\Models\Kantor;
use App\Models\Periode;
use Illuminate\Console\Command;

class RestoreKantorPeriode extends Command
{
    protected $signature   = 'restore:kantor-periode';
    protected $description = 'Pulihkan (restore) semua data kantor dan periode yang terhapus, atau isi ulang dari data bawaan jika semua sudah kosong.';

    public function handle(): void
    {
        // ─── 1. Pulihkan soft-deleted ─────────────────────────────────────────
        $kantorRestored  = Kantor::onlyTrashed()->count();
        $periodeRestored = Periode::onlyTrashed()->count();

        Kantor::onlyTrashed()->restore();
        Periode::onlyTrashed()->restore();

        if ($kantorRestored > 0 || $periodeRestored > 0) {
            $this->info("✅ Dipulihkan: {$kantorRestored} kantor, {$periodeRestored} periode dari tempat sampah.");
        }

        // ─── 2. Jika tabel masih kosong, isi ulang dari data bawaan ──────────
        if (Kantor::count() === 0) {
            $this->warn('⚠️  Tidak ada kantor sama sekali. Mengisi ulang data bawaan...');
            $this->call('db:seed', ['--class' => 'KantorSeeder']);
            $this->info('✅ Data bawaan kantor berhasil diisi ulang.');
        }

        if (Periode::count() === 0) {
            $this->warn('⚠️  Tidak ada periode sama sekali. Mengisi ulang data bawaan...');
            $this->call('db:seed', ['--class' => 'PeriodeSeeder']);
            $this->info('✅ Data bawaan periode berhasil diisi ulang.');
        }

        // ─── 3. Ringkasan akhir ───────────────────────────────────────────────
        $this->newLine();
        $this->table(
            ['Model', 'Jumlah Aktif'],
            [
                ['Kantor',  Kantor::count()],
                ['Periode', Periode::count()],
            ]
        );

        $this->info('Selesai. Sistem siap digunakan kembali.');
    }
}
