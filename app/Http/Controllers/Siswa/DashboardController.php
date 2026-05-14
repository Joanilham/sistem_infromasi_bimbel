<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $peserta = $user->pesertaDidik;

        if (!$peserta) {
            abort(403, 'Data peserta didik tidak ditemukan.');
        }

        $peserta->load(['paketBimbingan', 'kelompokBelajar', 'kantor', 'periode']);

        // Riwayat absensi 30 hari terakhir
        $absensis = Absensi::where('peserta_didik_id', $peserta->id)
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        // Statistik bulan ini
        $bulanIni = now()->startOfMonth();
        $totalHadir = Absensi::where('peserta_didik_id', $peserta->id)
            ->where('tanggal', '>=', $bulanIni)
            ->where('status_masuk', 'hadir')
            ->count();
        $totalAlpha = Absensi::where('peserta_didik_id', $peserta->id)
            ->where('tanggal', '>=', $bulanIni)
            ->where('status_masuk', 'alpha')
            ->count();

        // CBT: Ujian yang tersedia (aktif & belum selesai)
        $kelompokId = $peserta->kelompok_belajar_id;
        $assignedUjianIds = \App\Models\CbtUjianAssign::where(function($q) use ($user, $kelompokId) {
            $q->where('tipe_assign', 'user')->where('assign_id', $user->id);
            if ($kelompokId) {
                $q->orWhere(function($sq) use ($kelompokId) {
                    $sq->where('tipe_assign', 'kelas')->where('assign_id', $kelompokId);
                });
            }
        })->pluck('cbt_ujian_id')->toArray();

        $ujianAktif = \App\Models\CbtUjian::whereIn('id', $assignedUjianIds)
            ->aktif()
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        $ujianSelesaiIds = \App\Models\CbtPeserta::where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'timeout'])
            ->pluck('cbt_ujian_id')
            ->toArray();

        $ujianAktif = $ujianAktif->reject(function($ujian) use ($ujianSelesaiIds) {
            return in_array($ujian->id, $ujianSelesaiIds);
        });

        return view('siswa.dashboard', compact('user', 'peserta', 'absensis', 'totalHadir', 'totalAlpha', 'ujianAktif'));
    }
}
