<?php

namespace App\Services;

use App\Models\Pendaftaran\PendaftaranSiswa;
use App\Models\Keuangan\PembayaranPendaftaran;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Models\Akademik\PesertaDidik;
use App\Models\User;
use App\Models\MasterData\Kantor;
use App\Models\MasterData\Periode;
use App\Models\Akademik\PaketBimbingan;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * PendaftaranService
 *
 * Mengekstrak logika bisnis dari PendaftaranController@step3Store dan
 * PendaftaranController@adminVerifikasi agar controller tetap tipis.
 */
class PendaftaranService
{
    use HandlesImageUpload;

    /**
     * Proses penyimpanan pendaftaran baru (Step 3).
     * Membuat PendaftaranSiswa, User, dan PembayaranPendaftaran.
     *
     * @param  Request $request
     * @param  array   $sessionData  Data dari session ('daftar_data', 'daftar_email', dll)
     * @return PendaftaranSiswa
     * @throws \Exception
     */
    public function createPendaftaran(Request $request, array $sessionData): PendaftaranSiswa
    {
        $data      = $sessionData['data'];
        $email     = $sessionData['email'];
        $password  = $sessionData['password'];
        $kantorId  = $sessionData['kantor_id'];

        $kantor  = Kantor::find($kantorId) ?? Kantor::first();
        $periode = Periode::where('is_active', true)->first();

        $verifToken = Str::random(64);

        return DB::transaction(function () use ($request, $data, $email, $password, $kantor, $periode, $verifToken) {
            $pendaftaran = PendaftaranSiswa::create([
                'email'                    => $email,
                'password'                 => Hash::make($password),
                'email_verification_token' => $verifToken,
                'email_verified_at'        => null,
                'nama_lengkap'             => $data['nama_lengkap'],
                'nisn'                     => $data['nisn'] ?? null,
                'jenis_kelamin'            => $data['jenis_kelamin'],
                'tempat_lahir'             => $data['tempat_lahir'] ?? null,
                'tanggal_lahir'            => $data['tanggal_lahir'] ?? null,
                'agama'                    => $data['agama'] ?? null,
                'alamat_lengkap'           => $data['alamat_lengkap'] ?? null,
                'no_telepon'               => $data['no_telepon'] ?? null,
                'asal_sekolah'             => $data['asal_sekolah'],
                'paket_bimbingan_id'       => $data['paket_bimbingan_id'] ?? null,
                'kelompok_belajar_id'      => $data['kelompok_belajar_id'] ?? null,
                'informasi_dari'           => $data['informasi_dari'] ?? null,
                'nama_ayah'                => $data['nama_ayah'] ?? null,
                'pekerjaan_ayah'           => $data['pekerjaan_ayah'] ?? null,
                'no_telepon_ayah'          => $data['no_telepon_ayah'] ?? null,
                'nama_ibu'                 => $data['nama_ibu'] ?? null,
                'pekerjaan_ibu'            => $data['pekerjaan_ibu'] ?? null,
                'no_telepon_ibu'           => $data['no_telepon_ibu'] ?? null,
                'status'                   => 'menunggu',
                'kantor_id'                => $kantor?->id,
                'periode_id'               => $periode?->id,
            ]);

            // Buat akun User agar siswa bisa login
            User::create([
                'name'       => $pendaftaran->nama_lengkap,
                'email'      => $pendaftaran->email,
                'username'   => $pendaftaran->email,
                'password'   => $password,
                'level'      => 'siswa',
                'is_active'  => true,
                'status'     => 'menunggu',
                'kantor_id'  => $kantor?->id,
                'periode_id' => $periode?->id,
            ]);

            // Upload bukti pembayaran
            $file       = $request->file('bukti_pembayaran');
            $ext        = strtolower($file->getClientOriginalExtension());
            $path       = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])
                ? $this->compressAndStore($file, 'bukti_pembayaran')
                : $file->store('bukti_pembayaran', 'public');

            // Hitung jumlah pembayaran
            $jenisBayar    = $request->jenis_bayar;
            $jumlahDibayar = $this->hitungJumlahDibayar($data['paket_bimbingan_id'] ?? null, $jenisBayar);

            PembayaranPendaftaran::create([
                'pendaftaran_siswa_id' => $pendaftaran->id,
                'metode_pembayaran'    => $request->metode_pembayaran,
                'jumlah'               => $jumlahDibayar,
                'jenis_bayar'          => $jenisBayar,
                'bukti_pembayaran'     => $path,
                'status'               => 'menunggu',
            ]);

            return $pendaftaran;
        });
    }

    /**
     * Proses verifikasi pendaftaran oleh admin (terima atau tolak).
     *
     * @param  Request          $request
     * @param  PendaftaranSiswa $pendaftaran
     * @return string  Pesan sukses
     */
    public function verifikasi(Request $request, PendaftaranSiswa $pendaftaran): string
    {
        if ($request->aksi === 'diverifikasi') {
            $this->terimaVerifikasi($request, $pendaftaran);
        } else {
            $this->tolakVerifikasi($pendaftaran);
        }

        // Kirim notifikasi WhatsApp
        $this->kirimNotifikasiWa($request->aksi, $pendaftaran, $request->catatan_admin);

        return $request->aksi === 'diverifikasi'
            ? '✅ Pendaftaran diverifikasi! Akun siswa berhasil dibuat.'
            : '❌ Pendaftaran ditolak dan data telah dihapus.';
    }

    // ─── PRIVATE HELPERS ──────────────────────────────────

    private function terimaVerifikasi(Request $request, PendaftaranSiswa $pendaftaran): void
    {
        $peserta = PesertaDidik::create([
            'kantor_id'           => $pendaftaran->kantor_id,
            'periode_id'          => $pendaftaran->periode_id,
            'nama_lengkap'        => $pendaftaran->nama_lengkap,
            'nisn'                => $pendaftaran->nisn ?: null,
            'jenis_kelamin'       => $pendaftaran->jenis_kelamin,
            'tempat_lahir'        => $pendaftaran->tempat_lahir,
            'tanggal_lahir'       => $pendaftaran->tanggal_lahir,
            'agama'               => $pendaftaran->agama,
            'alamat_lengkap'      => $pendaftaran->alamat_lengkap,
            'no_telepon'          => $pendaftaran->no_telepon,
            'asal_sekolah'        => $pendaftaran->asal_sekolah,
            'paket_bimbingan_id'  => $pendaftaran->paket_bimbingan_id,
            'kelompok_belajar_id' => $request->kelompok_belajar_id,
            'informasi_dari'      => $pendaftaran->informasi_dari,
            'nama_ayah'           => $pendaftaran->nama_ayah,
            'pekerjaan_ayah'      => $pendaftaran->pekerjaan_ayah,
            'no_telepon_ayah'     => $pendaftaran->no_telepon_ayah,
            'nama_ibu'            => $pendaftaran->nama_ibu,
            'pekerjaan_ibu'       => $pendaftaran->pekerjaan_ibu,
            'no_telepon_ibu'      => $pendaftaran->no_telepon_ibu,
            'status'              => 'Aktif',
        ]);

        // Aktifkan akun User
        $userSiswa = User::where('email', $pendaftaran->email)->first();
        if ($userSiswa) {
            $userSiswa->update([
                'status'           => 'aktif',
                'peserta_didik_id' => $peserta->id,
            ]);
        }

        // Konfirmasi pembayaran
        if ($pendaftaran->pembayaran) {
            $pendaftaran->pembayaran->update(['status' => 'dikonfirmasi']);
        }

        // Catat transaksi otomatis jika ada
        $pembayaranSiswa = PembayaranSiswa::where('peserta_didik_id', $peserta->id)->first();
        if ($pembayaranSiswa && $pendaftaran->pembayaran && $pendaftaran->pembayaran->jumlah > 0) {
            $jenisBayarInfo = strtoupper($pendaftaran->pembayaran->jenis_bayar); // FULL atau DP
            $noKwitansi = TransaksiPembayaran::generateNoKwitansi($jenisBayarInfo);
            TransaksiPembayaran::create([
                'pembayaran_siswa_id' => $pembayaranSiswa->id,
                'nominal'             => $pendaftaran->pembayaran->jumlah,
                'tanggal'             => now(),
                'tipe_pembayaran'     => $pendaftaran->pembayaran->metode_pembayaran === 'Tunai' ? 'TUNAI' : 'TRANSFER',
                'no_kwitansi'         => $noKwitansi,
                'penerima'            => auth()->user()->name,
                'user_id'             => auth()->id(),
                'status'              => 'SUKSES',
                'catatan_siswa'       => "$jenisBayarInfo Pendaftaran (dibayar saat daftar)",
            ]);
        }

        // Update tagihan & batas waktu
        if ($pembayaranSiswa) {
            $this->updateTagihan($request, $pendaftaran, $pembayaranSiswa);
        }

        $pendaftaran->update([
            'status'              => 'diverifikasi',
            'catatan_admin'       => $request->catatan_admin,
            'kelompok_belajar_id' => $request->kelompok_belajar_id,
            'email_verified_at'   => now(),
        ]);
    }

    private function tolakVerifikasi(PendaftaranSiswa $pendaftaran): void
    {
        if ($pendaftaran->pembayaran) {
            $pendaftaran->pembayaran->delete();
        }

        $userSiswa = User::where('email', $pendaftaran->email)->first();
        if ($userSiswa) {
            $userSiswa->delete();
        }

        $pendaftaran->delete();
    }

    private function updateTagihan(Request $request, PendaftaranSiswa $pendaftaran, PembayaranSiswa $pembayaranSiswa): void
    {
        $nominalPaket  = $request->filled('nominal_paket')
            ? $request->nominal_paket
            : ($pendaftaran->paketBimbingan?->nominal ?? 0);

        $dpDibayar     = ($pendaftaran->pembayaran?->status === 'dikonfirmasi') ? ($pendaftaran->pembayaran->jumlah ?? 0) : 0;
        $sisaTagihan   = max(0, $nominalPaket - $dpDibayar);
        
        $paket = $pendaftaran->paketBimbingan;

        $jumlahCicilan = $request->jumlah_cicilan ?: null;
        if (!$jumlahCicilan && $paket && $paket->bisa_dicicil) {
            $jumlahCicilan = $paket->max_cicilan;
        }
        
        $nominalPerCicilan = ($jumlahCicilan > 0) ? (int) ceil($sisaTagihan / $jumlahCicilan) : null;

        $updateData = [
            'total_harus_dibayar'  => $nominalPaket,
            'jumlah_cicilan'       => $jumlahCicilan,
            'nominal_per_cicilan'  => $nominalPerCicilan,
        ];

        if ($request->filled('jatuh_tempo_berikutnya')) {
            $updateData['jatuh_tempo_berikutnya'] = $request->jatuh_tempo_berikutnya;
        } elseif ($jumlahCicilan > 0) {
            $updateData['jatuh_tempo_berikutnya'] = now()->addMonth();
        }

        // Hitung batas waktu dari durasi paket
        $paket = $pendaftaran->paketBimbingan;
        if ($paket && $paket->durasi_jumlah && $paket->durasi_satuan) {
            $durasiJumlah = $paket->durasi_jumlah;
            $durasiSatuan = strtolower($paket->durasi_satuan);
            $expiredDate = null;
            
            if ($durasiSatuan === 'bulan') {
                $expiredDate = now()->addMonths($durasiJumlah);
            } elseif ($durasiSatuan === 'tahun') {
                $expiredDate = now()->addYears($durasiJumlah);
            } elseif ($durasiSatuan === 'minggu') {
                $expiredDate = now()->addWeeks($durasiJumlah);
            } elseif ($durasiSatuan === 'hari') {
                $expiredDate = now()->addDays($durasiJumlah);
            }
            
            if ($expiredDate) {
                $updateData['batas_waktu'] = $expiredDate;
            }
        } elseif ($request->filled('batas_waktu')) {
            $updateData['batas_waktu'] = $request->batas_waktu;
        }

        $pembayaranSiswa->update($updateData);
    }

    private function hitungJumlahDibayar(?int $paketBimbinganId, string $jenisBayar): ?int
    {
        if (!$paketBimbinganId) {
            return null;
        }

        $paket = PaketBimbingan::find($paketBimbinganId);
        if (!$paket) {
            return null;
        }

        $nominal = $paket->nominal ?? 0;

        if ($jenisBayar === 'dp') {
            $dpPersen = $paket->dp_persen_minimal ?? 10;
            return (int) ceil($nominal * ($dpPersen / 100));
        }

        return $nominal;
    }

    private function kirimNotifikasiWa(string $aksi, PendaftaranSiswa $pendaftaran, ?string $catatanAdmin): void
    {
        $nomor = $pendaftaran->no_telepon ?? $pendaftaran->no_telepon_ayah ?? $pendaftaran->no_telepon_ibu;
        if (!$nomor) {
            return;
        }

        if ($aksi === 'diverifikasi') {
            $pesan = "🎉 *Pendaftaran Diterima*\n\nAssalamu'alaikum Bapak/Ibu,\n\nSelamat! Pendaftaran siswa atas nama:\n*{$pendaftaran->nama_lengkap}*\n\n✅ Telah *DIVERIFIKASI* dan diterima sebagai siswa aktif.\n\nAkun siswa telah dibuat:\n📧 Email: {$pendaftaran->email}\n\nSilakan login ke sistem untuk melihat informasi lebih lanjut.\n\nTerima kasih. 🙏";
        } else {
            $pesan = "❌ *Pendaftaran Ditolak*\n\nAssalamu'alaikum Bapak/Ibu,\n\nMohon maaf, pendaftaran siswa atas nama:\n*{$pendaftaran->nama_lengkap}*\n\nBelum dapat kami terima karena beberapa hal.\n\nCatatan: " . ($catatanAdmin ?? '-') . "\n\nTerima kasih atas pengertiannya. 🙏";
        }

        WhatsAppService::sendAsync($nomor, $pesan);
    }
}

