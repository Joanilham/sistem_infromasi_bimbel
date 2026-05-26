<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PembayaranSiswa;
use App\Models\User;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class CheckTenggatPembayaranCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pembayaran:check-tenggat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek keterlambatan cicilan: Kirim notifikasi H+1 dan Suspend H+7';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai pengecekan tenggat waktu pembayaran...");

        // Ambil pembayaran yang belum lunas dan punya tanggal jatuh tempo
        $pembayarans = PembayaranSiswa::with('pesertaDidik')
            ->whereNotNull('jatuh_tempo_berikutnya')
            ->get()
            ->filter(function ($p) {
                return $p->kekurangan > 0 && !$p->dispensasi;
            });

        $countNotif = 0;
        $countSuspend = 0;

        foreach ($pembayarans as $pembayaran) {
            $jatuhTempo = Carbon::parse($pembayaran->jatuh_tempo_berikutnya)->startOfDay();
            $hariIni = now()->startOfDay();
            
            // Berapa hari terlambat? (Positif artinya terlambat)
            $hariTerlambat = $jatuhTempo->diffInDays($hariIni, false);

            $peserta = $pembayaran->pesertaDidik;
            if (!$peserta) continue;

            $nomorHp = $peserta->no_telepon ?? $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu;

            // Jika telat 1 hari (H+1), kirim notifikasi
            if ($hariTerlambat == 1 && $nomorHp) {
                $pesan = "⚠️ *Peringatan Pembayaran Cicilan*\n\nAssalamu'alaikum Bapak/Ibu,\n\nKami informasikan bahwa tagihan cicilan ananda *{$peserta->nama_lengkap}* sebesar *Rp " . number_format($pembayaran->nominal_per_cicilan, 0, ',', '.') . "* telah melewati tanggal jatuh tempo ({$jatuhTempo->format('d M Y')}).\n\nMohon segera melakukan pembayaran melalui sistem agar akun tidak dinonaktifkan.\n\nTerima kasih. 🙏";
                
                WhatsAppService::sendAsync($nomorHp, $pesan);
                $countNotif++;
                $this->line("Notifikasi H+1 dikirim ke: {$peserta->nama_lengkap}");
            }
            
            // Jika telat lebih dari 7 hari (H+7 atau lebih), suspend akun
            if ($hariTerlambat >= 7) {
                $user = User::where('peserta_didik_id', $peserta->id)->first();
                if ($user && $user->status !== 'nonaktif') {
                    $user->update(['status' => 'nonaktif']);
                    $countSuspend++;
                    $this->error("Akun disuspend: {$peserta->nama_lengkap} (Telat {$hariTerlambat} hari)");

                    // Kirim notifikasi suspend
                    if ($nomorHp) {
                        $pesanSuspend = "❌ *Akun Dinonaktifkan Sementara*\n\nAssalamu'alaikum Bapak/Ibu,\n\nMohon maaf, dikarenakan belum ada pembayaran cicilan melewati 7 hari dari batas jatuh tempo, akun ananda *{$peserta->nama_lengkap}* kami *nonaktifkan sementara*.\n\nSilakan lunasi cicilan sebesar *Rp " . number_format($pembayaran->nominal_per_cicilan, 0, ',', '.') . "* untuk mengaktifkan kembali akun.\n\nTerima kasih. 🙏";
                        WhatsAppService::sendAsync($nomorHp, $pesanSuspend);
                    }
                }
            }
        }

        $this->info("Selesai! $countNotif notifikasi dikirim, $countSuspend akun disuspend.");
    }
}
