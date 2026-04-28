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
        $totalAbsen = Absensi::where('peserta_didik_id', $peserta->id)
            ->where('tanggal', '>=', $bulanIni)
            ->count();

        return view('siswa.dashboard', compact('user', 'peserta', 'absensis', 'totalHadir', 'totalAbsen'));
    }
}
