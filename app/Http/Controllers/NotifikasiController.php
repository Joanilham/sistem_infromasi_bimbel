<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranSiswa;
use App\Models\PembayaranPendaftaran;
use App\Models\TransaksiPembayaran;
use App\Models\PembayaranSiswa;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        $kantorId  = session('kantor_id');
        $periodeId = session('periode_id');

        // ── 1. Pendaftaran Menunggu Verifikasi ─────────────────────
        $pendaftaranMenunggu = PendaftaranSiswa::with(['kantor', 'paketBimbingan'])
            ->where('status', 'menunggu')
            ->when($kantorId, fn($q) => $q->where('kantor_id', $kantorId))
            ->latest()
            ->get();

        // ── 2. Pembayaran Pendaftaran Menunggu Konfirmasi ──────────
        $pembayaranBelumDikonfirmasi = PembayaranPendaftaran::with([
                'pendaftaranSiswa.kantor',
                'pendaftaranSiswa.paketBimbingan',
            ])
            ->where('status', 'menunggu')
            ->whereHas('pendaftaranSiswa', fn($q) =>
                $q->when($kantorId, fn($q2) => $q2->where('kantor_id', $kantorId))
            )
            ->latest()
            ->get();

        // ── 3. Transfer SPP (Transaksi TRANSFER 7 hari terakhir) ───
        $transferSpp = TransaksiPembayaran::with([
                'pembayaranSiswa.pesertaDidik.paketBimbingan',
                'user',
            ])
            ->where('tipe_pembayaran', 'TRANSFER')
            ->whereHas('pembayaranSiswa.pesertaDidik', function ($q) use ($kantorId, $periodeId) {
                $q->inContext();
            })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->latest()
            ->get();

        // ── 4. Tagihan Jatuh Tempo / Overdue ──────────────────────
        // Siswa belum lunas, deadline <= 3 hari ke depan atau sudah lewat
        $tagihanJatuhTempo = PembayaranSiswa::with([
                'pesertaDidik.paketBimbingan',
                'transaksi',
            ])
            ->whereHas('pesertaDidik', function ($q) use ($kantorId, $periodeId) {
                $q->inContext()->aktif();
            })
            ->where(function ($q) {
                $q->where('batas_waktu', '<=', Carbon::now()->addDays(7))
                  ->orWhere('batas_waktu', '<', Carbon::today())
                  ->orWhereNull('batas_waktu');
            })
            ->orderBy('batas_waktu', 'asc')
            ->get()
            ->filter(fn($p) => $p->kekurangan > 0); // hanya yang belum lunas

        return view('admin.notifikasi.index', compact(
            'pendaftaranMenunggu',
            'pembayaranBelumDikonfirmasi',
            'transferSpp',
            'tagihanJatuhTempo',
        ));
    }
}
