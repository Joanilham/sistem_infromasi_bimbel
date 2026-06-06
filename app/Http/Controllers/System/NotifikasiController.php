<?php

namespace App\Http\Controllers\System;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\PaketBimbingan;
use App\Models\MasterData\Kantor;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Pendaftaran\PendaftaranSiswa;
use App\Models\Keuangan\PembayaranPendaftaran;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Models\Keuangan\PembayaranSiswa;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $kantorId  = session('kantor_id');
        $periodeId = session('periode_id');
        $isSuperAdmin = auth()->check() && strtolower(auth()->user()->level) === 'super admin';
        $filterKantorId = $isSuperAdmin ? null : $kantorId;
        $search = $request->input('search', '');

        // Auto-create missing PembayaranSiswa records for active students in context
        if ($kantorId && $periodeId) {
            $studentsWithoutBilling = \App\Models\Akademik\PesertaDidik::aktif()
                ->inContext()
                ->whereDoesntHave('pembayaran')
                ->get();

            foreach ($studentsWithoutBilling as $student) {
                $student->pembayaran()->create([
                    'total_harus_dibayar' => $student->paketBimbingan?->nominal ?? 0,
                    'biaya_pendaftaran'   => 0,
                    'diskon_persen'       => 0,
                    'diskon_nominal'      => 0,
                ]);
            }
        }

        // ── 1. Pendaftaran Menunggu Verifikasi ─────────────────────
        $pendaftaranMenunggu = PendaftaranSiswa::with(['kantor', 'paketBimbingan'])
            ->where('status', 'menunggu')
            ->when($filterKantorId, fn($q) => $q->where('kantor_id', $filterKantorId))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhere('no_telepon', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();



        // ── 3. Transfer SPP (Transaksi TRANSFER 30 hari terakhir) ───
        $transferSpp = TransaksiPembayaran::with([
                'pembayaranSiswa.pesertaDidik.paketBimbingan',
                'user',
            ])
            ->where('tipe_pembayaran', 'TRANSFER')
            ->whereHas('pembayaranSiswa.pesertaDidik', function ($q) use ($search) {
                $q->inContext();
                if ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%");
                }
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('no_kwitansi', 'like', "%{$search}%")
                       ->orWhere('penerima', 'like', "%{$search}%");
                });
            })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->latest()
            ->get();

        // ── 4. Tagihan Jatuh Tempo / Overdue ──────────────────────
        // Siswa belum lunas, deadline <= 31 hari ke depan atau sudah lewat, atau belum diatur
        $tagihanJatuhTempo = PembayaranSiswa::with(['pesertaDidik.paketBimbingan'])
            ->withSum(['transaksi' => fn($q) => $q->where('status', 'sukses')], 'nominal')
            ->whereHas('pesertaDidik', function ($q) use ($search) {
                $q->inContext()->aktif();
                if ($search) {
                    $q->where(function ($q2) use ($search) {
                        $q2->where('nama_lengkap', 'like', "%{$search}%")
                           ->orWhere('nomor_induk', 'like', "%{$search}%");
                    });
                }
            })
            ->where(function ($q) {
                $q->where('batas_waktu', '<=', Carbon::now()->addDays(31))
                  ->orWhere('batas_waktu', '<', Carbon::today())
                  ->orWhereNull('batas_waktu');
            })
            ->orderBy('batas_waktu', 'asc')
            ->get()
            ->filter(fn($p) => $p->total_harus_dibayar - ($p->transaksi_sum_nominal ?? 0) > 0);

        return view('admin.notifikasi.index', compact(
            'pendaftaranMenunggu',
            'transferSpp',
            'tagihanJatuhTempo',
            'search',
        ));
    }
}




