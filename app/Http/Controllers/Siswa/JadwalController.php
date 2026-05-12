<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Tampilan jadwal mingguan rombel siswa.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $peserta = $user->pesertaDidik;

        if (!$peserta || !$peserta->kelompok_belajar_id) {
            return view('siswa.jadwal.index', [
                'jadwal'    => collect(),
                'peserta'   => $peserta,
                'hariIni'   => now()->locale('id')->isoFormat('dddd'),
            ]);
        }

        $jadwal = Jadwal::where('rombel_id', $peserta->kelompok_belajar_id)
            ->with(['guru', 'mataPelajaran'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        // Mapping nama hari ke Carbon dayOfWeek agar bisa highlight hari ini
        $hariIni = now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, dst

        return view('siswa.jadwal.index', compact('jadwal', 'peserta', 'hariIni'));
    }
}
