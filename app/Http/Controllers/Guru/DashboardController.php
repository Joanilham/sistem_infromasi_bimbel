<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user();
        
        $hariIni = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
        
        $jadwalHariIni = \App\Models\JadwalMapel::where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->count();
            
        $bankSoalCount = \App\Models\CbtBankSoal::count();
        
        $ujianAktifCount = \App\Models\CbtUjian::where(function($query) {
            $query->whereNull('waktu_selesai')
                  ->orWhere('waktu_selesai', '>=', now());
        })->count();
        
        $totalSiswa = \App\Models\User::where('level', 'siswa')->count();
        
        $recentUjians = \App\Models\CbtUjian::withCount('pesertas')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $jadwals = \App\Models\JadwalMapel::where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();
        
        return view('guru.dashboard', compact(
            'guru', 'jadwalHariIni', 'bankSoalCount', 'ujianAktifCount', 'totalSiswa', 'recentUjians', 'jadwals'
        ));
    }
}