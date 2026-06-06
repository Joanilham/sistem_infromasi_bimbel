<?php

namespace App\Http\Controllers\Siswa;
use App\Models\Akademik\PesertaDidik;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\MasterData\Bank;
use App\Models\Akademik\PaketBimbingan;
use App\Models\MasterData\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pesertaDidik = $user->pesertaDidik;

        if (!$pesertaDidik) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Ambil data pembayaran beserta transaksinya
        $pembayaran = $pesertaDidik->pembayaran()->with(['transaksi' => function($q) {
            $q->orderBy('tanggal', 'desc');
        }])->first();

        $kekurangan = $pembayaran ? $pembayaran->kekurangan : ($pesertaDidik->paketBimbingan?->nominal ?? 0);

        // Ambil data master untuk nomor WA
        $master = \App\Models\MasterData\Master::first();

        // Ambil rekening bank aktif
        $banks = \App\Models\MasterData\Bank::where('is_active', true)->get();

        return view('siswa.pembayaran.index', compact('pesertaDidik', 'pembayaran', 'master', 'banks', 'kekurangan'));
    }

    public function confirmPayment(Request $request)
    {
        $user = Auth::user();
        $pesertaDidik = $user->pesertaDidik;

        if (!$pesertaDidik) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $pembayaran = $pesertaDidik->pembayaran()->firstOrCreate(
            ['peserta_didik_id' => $pesertaDidik->id],
            [
                'total_harus_dibayar' => $pesertaDidik->paketBimbingan?->nominal ?? 0,
                'biaya_pendaftaran'   => 0,
                'diskon_persen'       => 0,
                'diskon_nominal'      => 0,
            ]
        );

        $validated = $request->validate([
            'nominal' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'bank_tujuan_id' => 'required|exists:banks,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'catatan_siswa' => 'nullable|string',
        ], [
            'nominal.required' => 'Jumlah nominal pembayaran harus diisi.',
            'nominal.integer' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal pembayaran minimal Rp 1.',
            'tanggal.required' => 'Tanggal pembayaran harus diisi.',
            'bank_tujuan_id.required' => 'Bank tujuan harus dipilih.',
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diunggah.',
            'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berformat JPEG, PNG, atau JPG.',
            'bukti_pembayaran.max' => 'Ukuran bukti pembayaran maksimal 2 MB.',
        ]);

        // Batasi nominal pembayaran agar tidak melebihi sisa tagihan
        $kekurangan = $pembayaran->kekurangan;
        if ($validated['nominal'] > $kekurangan) {
            return back()->withInput()->withErrors([
                'nominal' => 'Nominal pembayaran tidak boleh melebihi sisa tagihan Anda (Rp ' . number_format($kekurangan, 0, ',', '.') . ').'
            ]);
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = $file->hashName();
            $path = $file->storeAs('bukti_pembayaran', $filename, 'public');
            $validated['bukti_pembayaran'] = $path;
        }

        // Generate temporary receipt number
        $noKwitansi = 'PENDING/' . strtoupper(uniqid());

        $pembayaran->transaksi()->create([
            'nominal' => $validated['nominal'],
            'tanggal' => $validated['tanggal'],
            'bank_tujuan_id' => $validated['bank_tujuan_id'],
            'bukti_pembayaran' => $validated['bukti_pembayaran'],
            'catatan_siswa' => $validated['catatan_siswa'] ?? null,
            'status' => 'PENDING',
            'tipe_pembayaran' => 'TRANSFER',
            'no_kwitansi' => $noKwitansi,
            'penerima' => '-',
            'user_id' => $user->id,
        ]);

        return redirect()->route('siswa.pembayaran.index')->with('success', 'Konfirmasi pembayaran berhasil dikirim. Menunggu verifikasi dari admin.');
    }

    public function downloadNota(\App\Models\Keuangan\TransaksiPembayaran $transaksi)
    {
        // Pastikan transaksi milik siswa yang sedang login
        $user = Auth::user();
        if ($transaksi->pembayaranSiswa->pesertaDidik->user->id !== $user->id) {
            abort(403, 'Anda tidak berhak mengunduh nota ini.');
        }

        // Tampilkan view nota (bisa menggunakan window.print() di view)
        return view('siswa.pembayaran.nota', compact('transaksi'));
    }
}


