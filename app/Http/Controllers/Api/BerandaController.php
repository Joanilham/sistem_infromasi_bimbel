<?php

namespace App\Http\Controllers\Api;
use App\Models\Akademik\PesertaDidik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Akademik\Jadwal;
use App\Models\System\Pengumuman;
use App\Models\CBT\CbtUjian;
use App\Traits\ApiResponse;
use App\Http\Resources\PengumumanResource;
use App\Http\Resources\JadwalResource;
use App\Http\Resources\CbtUjianResource;

class BerandaController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get today's day in Indonesian (e.g., 'Senin', 'Selasa')
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

        // General Data for both
        $pengumumanData = Pengumuman::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $data = [
            'pengumuman' => PengumumanResource::collection($pengumumanData),
        ];

        // Specific Data based on User Role (Siswa vs Guru)
        // Check if user is linked to a PesertaDidik (Siswa)
        if ($user->peserta_didik_id && $user->pesertaDidik) {
            $peserta = $user->pesertaDidik;

            // 1. Jadwal Pelajaran Hari Ini
            if (strtolower($user->status) === 'aktif' && $peserta->kelompok_belajar_id) {
                $jadwalData = Jadwal::with(['mataPelajaran', 'guru'])
                    ->where('hari', $hariIni)
                    ->where('rombel_id', $peserta->kelompok_belajar_id)
                    ->orderBy('jam_mulai')
                    ->get();
                $data['jadwal_hari_ini'] = JadwalResource::collection($jadwalData);
            } else {
                $data['jadwal_hari_ini'] = [];
            }

            // 2. Info Keuangan / Tagihan
            $pembayaran = $peserta->pembayaran;
            if ($pembayaran && !$pembayaran->lunas) {
                $data['tagihan_aktif'] = [
                    'total_harus_dibayar' => $pembayaran->total_harus_dibayar,
                    'total_terbayar'      => $pembayaran->total_terbayar,
                    'kekurangan'          => $pembayaran->kekurangan,
                    'batas_waktu'         => $pembayaran->batas_waktu ? $pembayaran->batas_waktu->format('Y-m-d') : null,
                    'is_overdue'          => $pembayaran->batas_waktu && $pembayaran->batas_waktu->isPast()
                ];
            } else {
                $data['tagihan_aktif'] = null;
            }

            // 3. Ujian CBT Aktif
            if (strtolower($user->status) === 'aktif') {
                $ujianData = CbtUjian::aktif()
                    ->orderBy('waktu_mulai', 'asc')
                    ->take(3)
                    ->get(['id', 'judul', 'waktu_mulai', 'waktu_selesai', 'durasi']);
                $data['ujian_aktif'] = CbtUjianResource::collection($ujianData);
            } else {
                $data['ujian_aktif'] = [];
            }

            $data['role_view'] = 'siswa';
        } else {
            // Assume Guru or Admin (Focus on Guru)
            $jadwalData = Jadwal::with(['mataPelajaran', 'rombel'])
                ->where('hari', $hariIni)
                ->where('guru_id', $user->id)
                ->orderBy('jam_mulai')
                ->get();
            $data['jadwal_hari_ini'] = JadwalResource::collection($jadwalData);

            $data['role_view'] = 'guru';
        }

        // Cache response for 5 minutes (300 seconds) to save mobile data
        return $this->successResponse($data, 'Beranda berhasil dimuat')->header('Cache-Control', 'private, max-age=300');
    }
}


