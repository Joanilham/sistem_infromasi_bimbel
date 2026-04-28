<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\PesertaDidik;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $pesertaDidiks = PesertaDidik::aktif()->inContext()
            ->with(['paketBimbingan'])
            ->get()
            ->map(function ($p) use ($tanggal) {
                $p->absensi_hari_ini = Absensi::where('peserta_didik_id', $p->id)
                    ->where('tanggal', $tanggal)
                    ->first();
                return $p;
            });

        return view('admin.absensi.index', compact('pesertaDidiks', 'tanggal'));
    }

    // Absen masuk
    public function masuk(Request $request, PesertaDidik $peserta)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $absensi = Absensi::firstOrCreate(
            ['peserta_didik_id' => $peserta->id, 'tanggal' => $tanggal],
            ['status_masuk' => 'hadir', 'jam_masuk' => now()->format('H:i:s')]
        );

        if (!$absensi->jam_masuk) {
            $absensi->update(['jam_masuk' => now()->format('H:i:s'), 'status_masuk' => 'hadir']);
        }

        // Kirim WA ke orang tua
        if (!$absensi->wa_masuk_sent) {
            $this->kirimWaMasuk($peserta, $absensi);
            $absensi->update(['wa_masuk_sent' => true]);
        }

        return back()->with('success', $peserta->nama_lengkap . ' berhasil absen masuk.');
    }

    // Absen pulang
    public function pulang(Request $request, PesertaDidik $peserta)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $absensi = Absensi::where('peserta_didik_id', $peserta->id)
            ->where('tanggal', $tanggal)
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Siswa belum melakukan absen masuk.');
        }

        $absensi->update(['jam_pulang' => now()->format('H:i:s')]);

        // Kirim WA ke orang tua
        if (!$absensi->wa_pulang_sent) {
            $this->kirimWaPulang($peserta, $absensi);
            $absensi->update(['wa_pulang_sent' => true]);
        }

        return back()->with('success', $peserta->nama_lengkap . ' berhasil absen pulang.');
    }

    // Absen masuk via QR (by NISN)
    public function scanMasuk(Request $request)
    {
        $request->validate(['nisn' => 'required|string']);

        $peserta = PesertaDidik::where('nisn', $request->nisn)->first();
        if (!$peserta) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan.']);
        }

        $tanggal = now()->toDateString();
        $absensi = Absensi::firstOrCreate(
            ['peserta_didik_id' => $peserta->id, 'tanggal' => $tanggal],
            ['status_masuk' => 'hadir', 'jam_masuk' => now()->format('H:i:s')]
        );

        if (!$absensi->jam_masuk) {
            $absensi->update(['jam_masuk' => now()->format('H:i:s'), 'status_masuk' => 'hadir']);
        }

        if (!$absensi->wa_masuk_sent) {
            $this->kirimWaMasuk($peserta, $absensi);
            $absensi->update(['wa_masuk_sent' => true]);
        }

        return response()->json([
            'success'   => true,
            'message'   => $peserta->nama_lengkap . ' berhasil absen masuk.',
            'nama'      => $peserta->nama_lengkap,
            'nisn'      => $peserta->nisn,
            'tipe'      => 'masuk',
            'jam'       => $absensi->jam_masuk,
            'sudah'     => !$absensi->wasRecentlyCreated && $absensi->jam_masuk, // true jika sudah absen sebelumnya
        ]);
    }

    // Absen pulang via QR
    public function scanPulang(Request $request)
    {
        $request->validate(['nisn' => 'required|string']);

        $peserta = PesertaDidik::where('nisn', $request->nisn)->first();
        if (!$peserta) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan.']);
        }

        $tanggal = now()->toDateString();
        $absensi = Absensi::where('peserta_didik_id', $peserta->id)->where('tanggal', $tanggal)->first();

        if (!$absensi) {
            return response()->json(['success' => false, 'message' => 'Siswa belum absen masuk hari ini.']);
        }

        $absensi->update(['jam_pulang' => now()->format('H:i:s')]);

        if (!$absensi->wa_pulang_sent) {
            $this->kirimWaPulang($peserta, $absensi);
            $absensi->update(['wa_pulang_sent' => true]);
        }

        return response()->json([
            'success'   => true,
            'message'   => $peserta->nama_lengkap . ' berhasil absen pulang.',
            'nama'      => $peserta->nama_lengkap,
            'nisn'      => $peserta->nisn,
            'tipe'      => 'pulang',
            'jam'       => $absensi->jam_pulang,
        ]);
    }

    // Rekap absensi
    public function rekap(Request $request)
    {
        $bulan  = $request->input('bulan', now()->month);
        $tahun  = $request->input('tahun', now()->year);

        $pesertaDidiks = PesertaDidik::aktif()->inContext()
            ->with(['paketBimbingan'])
            ->get()
            ->map(function ($p) use ($bulan, $tahun) {
                $p->total_hadir = Absensi::where('peserta_didik_id', $p->id)
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->where('status_masuk', 'hadir')->count();
                $p->total_izin = Absensi::where('peserta_didik_id', $p->id)
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->where('status_masuk', 'izin')->count();
                $p->total_sakit = Absensi::where('peserta_didik_id', $p->id)
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->where('status_masuk', 'sakit')->count();
                $p->total_alpha = Absensi::where('peserta_didik_id', $p->id)
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->where('status_masuk', 'alpha')->count();
                return $p;
            });

        return view('admin.absensi.rekap', compact('pesertaDidiks', 'bulan', 'tahun'));
    }

    private function kirimWaMasuk(PesertaDidik $peserta, Absensi $absensi)
    {
        $nomorOrtu = $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu;
        if (!$nomorOrtu) return;

        $pesan = "📚 *Notifikasi Kehadiran*\n\n"
            . "Assalamu'alaikum Bapak/Ibu,\n\n"
            . "Kami informasikan bahwa putra/putri Anda:\n"
            . "*{$peserta->nama_lengkap}*\n\n"
            . "✅ Sudah *MASUK* bimbel pada:\n"
            . "📅 Tanggal: " . $absensi->tanggal->format('d M Y') . "\n"
            . "🕐 Jam: " . $absensi->jam_masuk . "\n\n"
            . "Terima kasih. 🙏";

        try {
            (new WhatsAppService())->sendMessage($nomorOrtu, $pesan);
        } catch (\Exception $e) {
            \Log::error('WA Masuk Error: ' . $e->getMessage());
        }
    }

    private function kirimWaPulang(PesertaDidik $peserta, Absensi $absensi)
    {
        $nomorOrtu = $peserta->no_telepon_ayah ?? $peserta->no_telepon_ibu;
        if (!$nomorOrtu) return;

        $pesan = "📚 *Notifikasi Kehadiran*\n\n"
            . "Assalamu'alaikum Bapak/Ibu,\n\n"
            . "Kami informasikan bahwa putra/putri Anda:\n"
            . "*{$peserta->nama_lengkap}*\n\n"
            . "🏠 Sudah *PULANG* dari bimbel pada:\n"
            . "📅 Tanggal: " . $absensi->tanggal->format('d M Y') . "\n"
            . "🕐 Jam: " . $absensi->jam_pulang . "\n\n"
            . "Terima kasih. 🙏";

        try {
            (new WhatsAppService())->sendMessage($nomorOrtu, $pesan);
        } catch (\Exception $e) {
            \Log::error('WA Pulang Error: ' . $e->getMessage());
        }
    }
}
