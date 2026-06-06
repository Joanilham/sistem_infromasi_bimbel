<?php

namespace App\Http\Controllers\Api;
use App\Models\Akademik\PesertaDidik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Akademik\Jadwal;
use App\Models\Akademik\Absensi;
use App\Traits\ApiResponse;
use App\Http\Resources\JadwalResource;
use App\Http\Resources\AbsensiResource;

class AkademikController extends Controller
{
    use ApiResponse;

    public function jadwal(Request $request)
    {
        $user = $request->user();
        $jadwal = collect();

        if ($user->peserta_didik_id && $user->pesertaDidik) {
            // Siswa
            $jadwal = Jadwal::with(['mataPelajaran', 'guru'])
                ->where('rombel_id', $user->pesertaDidik->kelompok_belajar_id)
                ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                ->orderBy('jam_mulai')
                ->get()
                ->groupBy('hari');
        } else {
            // Guru
            $jadwal = Jadwal::with(['mataPelajaran', 'rombel'])
                ->where('guru_id', $user->id)
                ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                ->orderBy('jam_mulai')
                ->get()
                ->groupBy('hari');
        }

        // Map group ke dalam JadwalResource
        $jadwalGrouped = $jadwal->map(function ($items) {
            return JadwalResource::collection($items);
        });

        return $this->successResponse($jadwalGrouped, 'Jadwal berhasil dimuat');
    }

    public function absensi(Request $request)
    {
        $user = $request->user();

        if (!$user->peserta_didik_id || !$user->pesertaDidik) {
            return $this->errorResponse('Data absensi hanya tersedia untuk siswa.', 403);
        }

        // Get absensi history for the student
        $absensi = Absensi::where('peserta_didik_id', $user->peserta_didik_id)
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return $this->successResponse([
            'data'         => AbsensiResource::collection($absensi->items()),
            'current_page' => $absensi->currentPage(),
            'last_page'    => $absensi->lastPage(),
        ], 'Riwayat absensi berhasil dimuat');
    }

    public function absensiToday(Request $request)
    {
        $user = $request->user();

        if (!$user->peserta_didik_id) {
            return $this->errorResponse('Data absensi hanya tersedia untuk siswa.', 403);
        }

        $today = now()->toDateString();
        $absensi = Absensi::where('peserta_didik_id', $user->peserta_didik_id)
            ->whereDate('tanggal', $today)
            ->first();

        return $this->successResponse(
            $absensi ? new AbsensiResource($absensi) : null, 
            'Status absensi hari ini dimuat'
        );
    }
}


