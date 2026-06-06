<?php

namespace App\Http\Controllers\Api;
use App\Models\Akademik\PesertaDidik;
use App\Models\MasterData\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Traits\ApiResponse;
use App\Http\Resources\TransaksiPembayaranResource;
use App\Http\Resources\BankResource;

class KeuanganController extends Controller
{
    use ApiResponse;

    public function tagihan(Request $request)
    {
        $user = $request->user();

        if (!$user->peserta_didik_id || !$user->pesertaDidik) {
            return $this->errorResponse('Data keuangan hanya tersedia untuk siswa.', 403);
        }

        $pembayaran = PembayaranSiswa::where('peserta_didik_id', $user->peserta_didik_id)->first();

        if (!$pembayaran) {
            return $this->successResponse(null, 'Tidak ada tagihan aktif');
        }

        $total_pending = $pembayaran->transaksi()->where('status', 'PENDING')->sum('nominal');

        $data = [
            'total_harus_dibayar' => $pembayaran->total_harus_dibayar,
            'total_terbayar'      => $pembayaran->total_terbayar,
            'total_menunggu'      => (int) $total_pending,
            'kekurangan'          => $pembayaran->kekurangan,
            'lunas'               => $pembayaran->lunas,
            'batas_waktu'         => $pembayaran->batas_waktu ? $pembayaran->batas_waktu->format('Y-m-d') : null,
            'rincian'             => [
                'biaya_pendaftaran' => $pembayaran->biaya_pendaftaran,
                'diskon_persen'     => $pembayaran->diskon_persen,
                'diskon_nominal'    => $pembayaran->diskon_nominal,
                'keterangan_diskon' => $pembayaran->keterangan_diskon,
            ]
        ];

        return $this->successResponse($data, 'Tagihan berhasil dimuat');
    }

    public function riwayat(Request $request)
    {
        $user = $request->user();

        if (!$user->peserta_didik_id || !$user->pesertaDidik) {
            return $this->errorResponse('Data keuangan hanya tersedia untuk siswa.', 403);
        }

        $pembayaranId = $user->pesertaDidik->pembayaran->id ?? null;

        if (!$pembayaranId) {
            return $this->successResponse([], 'Tidak ada riwayat pembayaran');
        }

        $transaksi = TransaksiPembayaran::with('bankTujuan')
            ->where('pembayaran_siswa_id', $pembayaranId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->successResponse([
            'data'         => TransaksiPembayaranResource::collection($transaksi->items()),
            'current_page' => $transaksi->currentPage(),
            'last_page'    => $transaksi->lastPage(),
        ], 'Riwayat pembayaran berhasil dimuat');
    }

    public function bank()
    {
        // Assuming Bank model is App\Models\MasterData\Bank
        $banks = \App\Models\MasterData\Bank::where('is_active', true)->get();
        return $this->successResponse(BankResource::collection($banks), 'Daftar bank tujuan berhasil dimuat');
    }

    public function bayar(Request $request)
    {
        $user = $request->user();

        if (!$user->peserta_didik_id || !$user->pesertaDidik) {
            return $this->errorResponse('Hanya siswa yang dapat melakukan pembayaran.', 403);
        }

        $pembayaran = PembayaranSiswa::where('peserta_didik_id', $user->peserta_didik_id)->first();
        if (!$pembayaran) {
            return $this->errorResponse('Tidak ada tagihan aktif.', 404);
        }

        $request->validate([
            'nominal' => 'required|numeric|min:10000',
            'bank_id' => 'required|exists:banks,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $kekurangan = $pembayaran->kekurangan;
        $total_pending = $pembayaran->transaksi()->where('status', 'PENDING')->sum('nominal');
        $sisa_boleh_dibayar = $kekurangan - $total_pending;

        if ($sisa_boleh_dibayar <= 0) {
            return $this->errorResponse('Tagihan Anda sudah lunas atau semua pembayaran sedang menunggu konfirmasi.', 422);
        }

        if ($request->nominal > $sisa_boleh_dibayar) {
            return $this->errorResponse("Nominal tidak boleh melebihi sisa tagihan dikurangi pembayaran yang menunggu konfirmasi (Maks: Rp " . number_format($sisa_boleh_dibayar, 0, ',', '.') . ").", 422);
        }

        // Buat no kwitansi: ambil 5 karakter pertama dari username/name saja agar tidak overflow
        $kodeUser = strtolower(substr($user->name ?? $user->username ?? 'sis', 0, 5));
        $noKwitansi = 'TMP-' . TransaksiPembayaran::generateNoKwitansi($kodeUser);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('pembayaran', 'public');
        }

        $transaksi = $pembayaran->transaksi()->create([
            'nominal' => $request->nominal,
            'tanggal' => now(),
            'tipe_pembayaran' => 'TRANSFER',
            'bank_tujuan_id' => $request->bank_id,
            'no_kwitansi' => $noKwitansi,
            'status' => 'PENDING',
            'bukti_pembayaran' => $buktiPath,
            'user_id' => $user->id,
            'penerima' => 'Sistem (Mobile)',
            'catatan_siswa' => 'Pembayaran via Aplikasi Mobile',
        ]);

        return $this->successResponse(new TransaksiPembayaranResource($transaksi), 'Pengajuan pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }
}


