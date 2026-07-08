<?php

namespace App\Console\Commands;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\Absensi;

use Illuminate\Console\Command;
use App\Models\Keuangan\PembayaranSiswa;
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
    protected $description = 'Kirim notifikasi tagihan H-3, peringatan H+2, dan suspend H+5.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai pengecekan tenggat waktu pembayaran...");

        // Ambil pembayaran yang belum lunas dan punya batas_waktu
        $pembayarans = PembayaranSiswa::with(['pesertaDidik', 'pesertaDidik.user', 'pesertaDidik.paketBimbingan'])
            ->whereNotNull('batas_waktu')
            ->get()
            ->filter(function ($p) {
                return $p->kekurangan > 0 && !$p->dispensasi;
            });

        $countHMinus3 = 0;
        $countHPlus2 = 0;
        $countSuspend = 0;

        foreach ($pembayarans as $pembayaran) {
            $jatuhTempo = Carbon::parse($pembayaran->batas_waktu)->startOfDay();
            $hariIni = now()->startOfDay();
            
            // Positif = terlambat (H+), Negatif = belum jatuh tempo (H-)
            $hariTerlambat = $jatuhTempo->diffInDays($hariIni, false);

            $peserta = $pembayaran->pesertaDidik;
            if (!$peserta) continue;

            $nomorHp = $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu ?? $peserta->no_telepon;
            $namaOrtu = "Bapak/Ibu";
            $namaAnak = $peserta->nama_lengkap;
            $namaPaket = $peserta->paketBimbingan->nama_paket ?? 'Bimbingan Belajar';
            $tglJatuhTempo = $jatuhTempo->format('d M Y');
            $nominal = number_format($pembayaran->kekurangan, 0, ',', '.');
            $linkPembayaran = config('app.url');

            // --- 1. SCRIPT H-3 JATUH TEMPO (Friendly Reminder) ---
            if ($hariTerlambat == -3 && $nomorHp) {
                $pesan = "Halo Ayah/Bunda *{$namaOrtu}*,\n\nSemoga sehat selalu. Kami dari *Sistem Akademik* ingin menginfokan bahwa tagihan cicilan untuk Paket Bimbel *{$namaPaket}* (Ananda: {$namaAnak}) akan jatuh tempo pada:\n\n📅 *Tanggal:* {$tglJatuhTempo}\n💰 *Nominal:* Rp {$nominal}\n\n*Catatan Sistem:*\nJika Anda menggunakan metode Autodebit, mohon pastikan saldo Anda mencukupi. Jika menggunakan transfer manual, pembayaran bisa dilakukan via link resmi di bawah ini:\n\n🔗 {$linkPembayaran}\n\nTerima kasih atas kerja samanya untuk kelancaran belajar Ananda. 🙏";
                
                WhatsAppService::sendAsync($nomorHp, $pesan);
                $countHMinus3++;
                $this->line("Notifikasi H-3 dikirim ke ortu: {$namaAnak}");
            }
            
            // --- 2. SCRIPT H+2 TERLAMBAT (Urgensi Ringan) ---
            if ($hariTerlambat == 2 && $nomorHp) {
                $pesan = "⚠️ *[PENTING - PENGINGAT SISTEM]* ⚠️\n\nHalo Ayah/Bunda *{$namaOrtu}*,\n\nSistem IT kami mendeteksi bahwa pembayaran cicilan Paket Bimbel untuk *{$namaAnak}* yang jatuh tempo pada {$tglJatuhTempo} *BELUM TERIMA* atau gagal terdebit otomatis.\n\n📊 *Detail Keterlambatan:*\n• Total Tagihan: Rp {$nominal}\n• Status: Terlambat 2 Hari\n\nMohon segera selesaikan pembayaran agar proses belajar Ananda tidak terganggu. *Sistem akan membekukan akun belajar Ananda secara otomatis dalam 48 jam ke depan jika tidak ada pelunasan.*\n\nKlik tautan aman ini untuk membayar sekarang:\n🔗 {$linkPembayaran}";
                
                WhatsAppService::sendAsync($nomorHp, $pesan);
                $countHPlus2++;
                $this->line("Notifikasi H+2 dikirim ke ortu: {$namaAnak}");
            }

            // --- 3. SCRIPT H+5 BLOCKING (Akses Dinonaktifkan) ---
            if ($hariTerlambat == 5) {
                $user = $peserta->user;
                if ($user && $user->status !== 'nonaktif') {
                    // Suspend user
                    $user->update(['status' => 'nonaktif']);
                    $countSuspend++;
                    $this->error("Akun disuspend H+5: {$namaAnak}");

                    if ($nomorHp) {
                        $pesan = "🚨 *[NOTIFIKASI OTOMATIS: AKSES BELAJAR DINONAKTIFKAN]* 🚨\n\nHalo Ayah/Bunda *{$namaOrtu}*,\n\nMohon maaf, karena belum adanya pembayaran cicilan yang melewati batas toleransi sistem (Jatuh tempo: {$tglJatuhTempo}), per hari ini *sistem IT telah menonaktifkan sementara akses belajar {$namaAnak}*.\n\n*Dampak Penonaktifan:*\n1. Tidak bisa login ke aplikasi/web belajar.\n2. Tidak terdaftar di absensi kelas.\n3. Akses materi & rekaman kelas dikunci.\n\nAkses akan *OTOMATIS AKTIF KEMBALI DALAM 5 MENIT* setelah Anda melakukan pembayaran via link instan di bawah ini:\n\n🔗 {$linkPembayaran}\n\nJika sudah membayar atau ada kendala sistem, silakan balas pesan ini dengan menyertakan bukti transfer. Terima kasih.";
                        
                        WhatsAppService::sendAsync($nomorHp, $pesan);
                    }
                }
            }
        }

        $this->info("Selesai! $countHMinus3 notif H-3, $countHPlus2 notif H+2, $countSuspend disuspend (H+5).");
    }
}



