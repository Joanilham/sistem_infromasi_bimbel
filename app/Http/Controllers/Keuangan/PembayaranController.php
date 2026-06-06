<?php

namespace App\Http\Controllers\Keuangan;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\Akademik\PesertaDidik;
use App\Models\Keuangan\TransaksiPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Keuangan\PembayaranRequest;
use App\Http\Requests\Keuangan\VerifikasiPembayaranRequest;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('ensure_role:super admin')->only([
            'destroyTransaksi',
            'editTransaksi',
            'updateTransaksi'
        ]);
    }

    // ── Daftar Siswa ────────────────────────────────────────────
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort    = in_array($request->input('sort'), ['id', 'nama_lengkap', 'batas_waktu']) ? $request->input('sort') : 'id';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        // Siswa dibiarkan tetap dalam cabang (inContext) karena siswa memang terikat pada cabang tertentu
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

        // [PERBAIKAN] inContext() pada PaketBimbingan dan KelompokBelajar dihapus karena sudah menjadi global
        $pakets = \App\Models\Akademik\PaketBimbingan::all();
        $kelompoks = \App\Models\Akademik\KelompokBelajar::all();
        
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
            'dispensasi'        => 'nullable|boolean',
        ]);

        $biaya   = $pembayaranSiswa->pesertaDidik->paketBimbingan?->nominal ?? 0;
        
        // Preserve existing values if not provided in the request
        $diskon  = $request->has('diskon_nominal') ? ($validated['diskon_nominal'] ?? 0) : $pembayaranSiswa->diskon_nominal;
        $biayaDaftar = $request->has('biaya_pendaftaran') ? ($validated['biaya_pendaftaran'] ?? 0) : $pembayaranSiswa->biaya_pendaftaran;
        
        $total   = $biaya - $diskon + $biayaDaftar;

        $validated['dispensasi'] = $request->has('dispensasi') ? (bool) $request->dispensasi : false;

        $pembayaranSiswa->update(array_merge($validated, ['total_harus_dibayar' => max(0, $total)]));

        return back()->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    // ── Tambah Transaksi ─────────────────────────────────────────
    public function storeTransaksi(PembayaranRequest $request, PembayaranSiswa $pembayaranSiswa)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $noKwitansi = TransaksiPembayaran::generateNoKwitansi($user->username ?? substr($user->name, 0, 3));

        $transaksi = $pembayaranSiswa->transaksi()->create(array_merge($validated, [
            'no_kwitansi' => $noKwitansi,
            'penerima'    => $user->name,
            'user_id'     => $user->id,
            'status'      => 'SUKSES', // Transaksi yang diinput admin langsung dianggap sukses
        ]));

        // Kirim Notifikasi WA
        $this->kirimBuktiWa($pembayaranSiswa->pesertaDidik, $validated['nominal'], $validated['tanggal'], $noKwitansi, false);

        return redirect()->route('keuangan.transaksi.struk', $transaksi->id)->with('success', "Pembayaran dicatat. No. Kwitansi: {$noKwitansi}");
    }

    // ── Halaman Struk ────────────────────────────────────────────
    public function strukTransaksi(TransaksiPembayaran $transaksiPembayaran)
    {
        $transaksiPembayaran->load('pembayaranSiswa.pesertaDidik');
        return view('keuangan.pembayaran.struk', compact('transaksiPembayaran'));
    }

    // ── Hapus Transaksi ──────────────────────────────────────────
    public function destroyTransaksi(TransaksiPembayaran $transaksiPembayaran)
    {
        $transaksiPembayaran->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    // ── Edit Transaksi ───────────────────────────────────────────
    public function editTransaksi(TransaksiPembayaran $transaksiPembayaran)
    {
        $pesertaDidik = $transaksiPembayaran->pembayaranSiswa->pesertaDidik;
        return view('keuangan.pembayaran.edit_transaksi', compact('transaksiPembayaran', 'pesertaDidik'));
    }

    // ── Update Transaksi ─────────────────────────────────────────
    public function updateTransaksi(PembayaranRequest $request, TransaksiPembayaran $transaksiPembayaran)
    {
        $validated = $request->validated();

        $transaksiPembayaran->update($validated);

        return redirect()->route('keuangan.pembayaran.show', $transaksiPembayaran->pembayaranSiswa->peserta_didik_id)
            ->with('success', 'Transaksi pembayaran berhasil diperbarui.');
    }

    // ── Verifikasi Transaksi ─────────────────────────────────────
    public function verifikasiTransaksi(VerifikasiPembayaranRequest $request, TransaksiPembayaran $transaksiPembayaran)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $noKwitansi = TransaksiPembayaran::generateNoKwitansi($user->username ?? substr($user->name, 0, 3));

        $transaksiPembayaran->update([
            'nominal'         => $validated['nominal'],
            'tipe_pembayaran' => $validated['tipe_pembayaran'],
            'status'          => 'SUKSES',
            'no_kwitansi'     => $noKwitansi,
            'penerima'        => $user->name,
            'user_id'         => $user->id,
        ]);

        // Kirim Notifikasi WA ke Orang Tua / Siswa
        $this->kirimBuktiWa(
            $transaksiPembayaran->pembayaranSiswa->pesertaDidik,
            $validated['nominal'],
            $transaksiPembayaran->tanggal,
            $noKwitansi,
            true
        );

        return redirect()->route('keuangan.transaksi.struk', $transaksiPembayaran->id)->with('success', "Pembayaran transfer berhasil diverifikasi. No. Kwitansi: {$noKwitansi}");
    }

    // ── Tolak Transaksi ──────────────────────────────────────────
    public function tolakTransaksi(Request $request, TransaksiPembayaran $transaksiPembayaran)
    {
        $transaksiPembayaran->update([
            'status' => 'DITOLAK',
            'alasan_penolakan' => $request->catatan_penolakan,
        ]);

        return back()->with('success', 'Pengajuan pembayaran berhasil ditolak.');
    }

    // ═══════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════

    /**
     * Kirim notifikasi WA bukti pembayaran.
     */
    private function kirimBuktiWa(PesertaDidik $peserta, int $nominalDb, string $tanggal, string $noKwitansi, bool $isVerifikasi = false): void
    {
        $nomor = $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu ?? $peserta->no_telepon;
        if (!$nomor) return;

        $nominalRp = number_format($nominalDb, 0, ',', '.');
        $tglStr    = date('d/m/Y', strtotime($tanggal));

        if ($isVerifikasi) {
            $judul = "💸 *Verifikasi Pembayaran Berhasil*";
            $barisTengah = "Pembayaran transfer untuk siswa:\n*{$peserta->nama_lengkap}*\n\n✅ Telah diverifikasi & diterima sebesar:";
        } else {
            $judul = "💸 *Bukti Pembayaran*";
            $barisTengah = "Pembayaran untuk siswa:\n*{$peserta->nama_lengkap}*\n\n✅ Telah diterima sebesar:";
        }

        $pesan = "{$judul}\n\nTerima kasih Bapak/Ibu,\n\n{$barisTengah}\n*Rp {$nominalRp}*\n📅 Tanggal: {$tglStr}\n🧾 No. Kwitansi: *{$noKwitansi}*\n\nSimpan pesan ini sebagai bukti pembayaran sah. 🙏";
        
        \App\Services\WhatsAppService::sendAsync($nomor, $pesan);
    }
}

