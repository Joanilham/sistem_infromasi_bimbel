<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSiswa;
use App\Models\PesertaDidik;
use App\Models\TransaksiPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // ── Daftar Siswa ────────────────────────────────────────────
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort    = in_array($request->input('sort'), ['id', 'nama_lengkap', 'batas_waktu']) ? $request->input('sort') : 'id';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        $siswaQuery = PesertaDidik::with(['paketBimbingan', 'pembayaran.transaksi'])
            ->inContext()
            ->aktif()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('nomor_induk', 'like', "%{$search}%");
                });
            })
            ->when($request->paket_id, fn($q) => $q->where('paket_id', $request->paket_id))
            ->when($request->kelompok_id, fn($q) => $q->where('kelompok_id', $request->kelompok_id));

        if ($sort === 'batas_waktu') {
            $siswaQuery->leftJoin('pembayaran_siswa', 'peserta_didiks.id', '=', 'pembayaran_siswa.peserta_didik_id')
                ->select('peserta_didiks.*')
                ->orderByRaw('pembayaran_siswa.batas_waktu IS NULL, pembayaran_siswa.batas_waktu ' . $order);
        } else {
            $siswaQuery->orderBy('peserta_didiks.' . $sort, $order);
        }

        $siswa = $siswaQuery->paginate($perPage)->withQueryString();

        $pakets = \App\Models\PaketBimbingan::inContext()->get();
        $kelompoks = \App\Models\KelompokBelajar::inContext()->get();
        return view('keuangan.pembayaran.index', compact('siswa', 'search', 'perPage', 'pakets', 'kelompoks'));
    }

    // ── Detail Pembayaran Siswa ──────────────────────────────────
    public function show(PesertaDidik $pesertaDidik)
    {
        $pesertaDidik->load(['paketBimbingan']);

        $pembayaran = PembayaranSiswa::with(['transaksi.user'])
            ->firstOrCreate(
                ['peserta_didik_id' => $pesertaDidik->id],
                [
                    'total_harus_dibayar' => $pesertaDidik->paketBimbingan?->nominal ?? 0,
                    'biaya_pendaftaran'   => 0,
                    'diskon_persen'       => 0,
                    'diskon_nominal'      => 0,
                ]
            );

        return view('keuangan.pembayaran.show', compact('pesertaDidik', 'pembayaran'));
    }

    // ── Update Diskon & Biaya Pendaftaran ────────────────────────
    public function update(Request $request, PembayaranSiswa $pembayaranSiswa)
    {
        $validated = $request->validate([
            'diskon_persen'     => 'nullable|numeric|min:0|max:100',
            'diskon_nominal'    => 'nullable|integer|min:0',
            'keterangan_diskon' => 'nullable|string|max:255',
            'biaya_pendaftaran' => 'nullable|integer|min:0',
            'batas_waktu'       => 'nullable|date',
        ]);

        $biaya   = $pembayaranSiswa->pesertaDidik->paketBimbingan?->nominal ?? 0;
        
        // Preserve existing values if not provided in the request
        $diskon  = $request->has('diskon_nominal') ? ($validated['diskon_nominal'] ?? 0) : $pembayaranSiswa->diskon_nominal;
        $biayaDaftar = $request->has('biaya_pendaftaran') ? ($validated['biaya_pendaftaran'] ?? 0) : $pembayaranSiswa->biaya_pendaftaran;
        
        $total   = $biaya - $diskon + $biayaDaftar;

        $pembayaranSiswa->update(array_merge($validated, ['total_harus_dibayar' => max(0, $total)]));

        return back()->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    // ── Tambah Transaksi ─────────────────────────────────────────
    public function storeTransaksi(Request $request, PembayaranSiswa $pembayaranSiswa)
    {
        $validated = $request->validate([
            'nominal'         => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'tipe_pembayaran' => 'required|in:TUNAI,TRANSFER',
        ]);

        $user = Auth::user();
        $noKwitansi = TransaksiPembayaran::generateNoKwitansi($user->username ?? substr($user->name, 0, 3));

        $pembayaranSiswa->transaksi()->create(array_merge($validated, [
            'no_kwitansi' => $noKwitansi,
            'penerima'    => $user->name,
            'user_id'     => $user->id,
        ]));

        // Kirim Notifikasi WA
        $peserta = $pembayaranSiswa->pesertaDidik;
        $nomor   = $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu ?? $peserta->no_telepon;
        if ($nomor) {
            dispatch(function () use ($peserta, $validated, $noKwitansi) {
                try {
                    $nominal = number_format($validated['nominal'], 0, ',', '.');
                    $pesan   = "💸 *Bukti Pembayaran*\n\nTerima kasih Bapak/Ibu,\n\nPembayaran untuk siswa:\n*{$peserta->nama_lengkap}*\n\n✅ Telah diterima sebesar:\n*Rp {$nominal}*\n📅 Tanggal: " . date('d/m/Y', strtotime($validated['tanggal'])) . "\n🧾 No. Kwitansi: *{$noKwitansi}*\n\nSimpan pesan ini sebagai bukti pembayaran sah. 🙏";
                    (new \App\Services\WhatsAppService())->sendMessage($nomor, $pesan);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Gagal kirim WA Pembayaran: " . $e->getMessage());
                }
            })->afterResponse();
        }

        return back()->with('success', "Pembayaran dicatat. No. Kwitansi: {$noKwitansi}");
    }

    // ── Hapus Transaksi ──────────────────────────────────────────
    public function destroyTransaksi(TransaksiPembayaran $transaksiPembayaran)
    {
        if (strtolower(Auth::user()->level) !== 'super admin') {
            abort(403, 'Hanya Super Admin yang berwenang mengedit atau menghapus transaksi SPP.');
        }

        $transaksiPembayaran->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    // ── Edit Transaksi ───────────────────────────────────────────
    public function editTransaksi(TransaksiPembayaran $transaksiPembayaran)
    {
        if (strtolower(Auth::user()->level) !== 'super admin') {
            abort(403, 'Hanya Super Admin yang berwenang mengedit atau menghapus transaksi SPP.');
        }

        $pesertaDidik = $transaksiPembayaran->pembayaranSiswa->pesertaDidik;
        return view('keuangan.pembayaran.edit_transaksi', compact('transaksiPembayaran', 'pesertaDidik'));
    }

    // ── Update Transaksi ─────────────────────────────────────────
    public function updateTransaksi(Request $request, TransaksiPembayaran $transaksiPembayaran)
    {
        if (strtolower(Auth::user()->level) !== 'super admin') {
            abort(403, 'Hanya Super Admin yang berwenang mengedit atau menghapus transaksi SPP.');
        }

        $validated = $request->validate([
            'nominal'         => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'tipe_pembayaran' => 'required|in:TUNAI,TRANSFER',
        ]);

        $transaksiPembayaran->update($validated);

        return redirect()->route('keuangan.pembayaran.show', $transaksiPembayaran->pembayaranSiswa->peserta_didik_id)
            ->with('success', 'Transaksi pembayaran berhasil diperbarui.');
    }
}
