<?php

namespace App\Http\Controllers\Api;
use App\Models\Akademik\PesertaDidik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        // Cache per user per jam — beranda data jarang berubah dalam 5 menit
        $cacheKey = "beranda_user_{$user->id}_" . Carbon::now()->format('YmdH') . '_' . (int)(Carbon::now()->minute / 5);

        $data = Cache::remember($cacheKey, 300, function () use ($user) {
            // Get today's day in Indonesian (e.g., 'Senin', 'Selasa')
            $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

            // General Data for both
            $pengumumanData = Pengumuman::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $result = [
                'pengumuman' => PengumumanResource::collection($pengumumanData),
            ];

            // Specific Data based on User Role (Siswa vs Guru)
            if ($user->peserta_didik_id && $user->pesertaDidik) {
                $peserta = $user->pesertaDidik;

                // 1. Jadwal Pelajaran Hari Ini
                if (strtolower($user->status) === 'aktif' && $peserta->kelompok_belajar_id) {
                    $jadwalData = Jadwal::with(['mataPelajaran', 'guru'])
                        ->where('hari', $hariIni)
                        ->where('rombel_id', $peserta->kelompok_belajar_id)
                        ->orderBy('jam_mulai')
                        ->get();
                    $result['jadwal_hari_ini'] = JadwalResource::collection($jadwalData);
                } else {
                    $result['jadwal_hari_ini'] = [];
                }

                // 2. Info Keuangan / Tagihan
                $pembayaran = $peserta->pembayaran;
                if ($pembayaran && !$pembayaran->lunas) {
                    $result['tagihan_aktif'] = [
                        'total_harus_dibayar' => $pembayaran->total_harus_dibayar,
                        'total_terbayar'      => $pembayaran->total_terbayar,
                        'kekurangan'          => $pembayaran->kekurangan,
                        'batas_waktu'         => $pembayaran->batas_waktu ? $pembayaran->batas_waktu->format('Y-m-d') : null,
                        'is_overdue'          => $pembayaran->batas_waktu && $pembayaran->batas_waktu->isPast()
                    ];
                } else {
                    $result['tagihan_aktif'] = null;
                }

                // 3. Ujian CBT Aktif
                if (strtolower($user->status) === 'aktif') {
                    $ujianData = CbtUjian::where(function ($q) {
                            $q->whereNull('waktu_selesai')
                              ->orWhere('waktu_selesai', '>=', now());
                        })
                        ->orderBy('waktu_mulai', 'asc')
                        ->take(3)
                        ->get(['id', 'judul', 'waktu_mulai', 'waktu_selesai', 'durasi']);
                    $result['ujian_aktif'] = CbtUjianResource::collection($ujianData);
                } else {
                    $result['ujian_aktif'] = [];
                }

                $result['role_view'] = 'siswa';
            } else {
                // Assume Guru or Admin
                $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
                $jadwalData = Jadwal::with(['mataPelajaran', 'rombel'])
                    ->where('hari', $hariIni)
                    ->where('guru_id', $user->id)
                    ->orderBy('jam_mulai')
                    ->get();
                $result['jadwal_hari_ini'] = JadwalResource::collection($jadwalData);
                $result['role_view'] = 'guru';
            }

            return $result;
        });

        return $this->successResponse($data, 'Beranda berhasil dimuat')
            ->header('Cache-Control', 'private, max-age=300');
    }
}


