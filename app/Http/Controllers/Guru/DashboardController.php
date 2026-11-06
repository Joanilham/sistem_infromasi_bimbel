<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalMapel;
use App\Models\Akademik\LmsContent;
use App\Models\Akademik\LmsSubmission;
use App\Models\CBT\CbtBankSoal;
use App\Models\CBT\CbtUjian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user();
        
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
        
        $jadwalHariIni = JadwalMapel::where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->count();
            
        $bankSoalCount = CbtBankSoal::count();
        
        $ujianAktifCount = CbtUjian::where(function($query) {
            $query->whereNull('waktu_selesai')
                  ->orWhere('waktu_selesai', '>=', now());
        })->count();
        
        $totalSiswa = User::where('level', 'siswa')->count();
        
        $recentUjians = CbtUjian::withCount('pesertas')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $jadwals = JadwalMapel::where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Notifikasi & Pengumpulan LMS Guru
        $notifikasiGuru = collect();
        $unreadNotifCount = 0;
        if (Schema::hasTable('notifications')) {
            $notifikasiGuru = $guru->notifications()->latest()->take(15)->get();
            $unreadNotifCount = $guru->unreadNotifications()->count();
        }

        // Statistik & Aktivitas LMS Guru
        $totalTugasLms = 0;
        $pendingReviewCount = 0;
        $recentTugas = collect();
        $recentSubmissions = collect();

        if (Schema::hasTable('lms_contents')) {
            $totalTugasLms = LmsContent::where('guru_id', $guru->id)->where('type', 'tugas')->count();
            $recentTugas = LmsContent::where('guru_id', $guru->id)
                ->where('type', 'tugas')
                ->with(['kelompokBelajar'])
                ->withCount('submissions')
                ->latest()
                ->take(5)
                ->get();

            if (Schema::hasTable('lms_submissions')) {
                $pendingReviewCount = LmsSubmission::whereHas('content', fn($q) => $q->where('guru_id', $guru->id))
                    ->whereNull('graded_at')
                    ->count();

                $recentSubmissions = LmsSubmission::whereHas('content', fn($q) => $q->where('guru_id', $guru->id))
                    ->with(['content.kelompokBelajar', 'pesertaDidik.kelompokBelajar'])
                    ->latest('submitted_at')
                    ->take(10)
                    ->get();
            }
        }
        
        return view('guru.dashboard', compact(
            'guru', 'jadwalHariIni', 'bankSoalCount', 'ujianAktifCount', 'totalSiswa', 'recentUjians', 'jadwals',
            'notifikasiGuru', 'unreadNotifCount', 'totalTugasLms', 'pendingReviewCount', 'recentTugas', 'recentSubmissions'
        ));
    }
}
