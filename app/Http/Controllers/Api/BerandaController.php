<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use App\Models\CbtUjian;
use App\Traits\ApiResponse;

class BerandaController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get today's day in Indonesian (e.g., 'Senin', 'Selasa')
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

        // General Data for both
        $pengumuman = Pengumuman::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($p) {
                if ($p->foto) {
                    $p->foto = asset('storage/' . $p->foto);
                }
                return $p;
            });

        $data = [
            'pengumuman' => $pengumuman,
        ];

        // Specific Data based on User Role (Siswa vs Guru)
        // Check if user is linked to a PesertaDidik (Siswa)
        if ($user->peserta_didik_id && $user->pesertaDidik) {
            $peserta = $user->pesertaDidik;

            // 1. Jadwal Pelajaran Hari Ini
            if ($peserta->kelompok_belajar_id) {
                $data['jadwal_hari_ini'] = Jadwal::with(['mataPelajaran', 'guru'])
                    ->where('hari', $hariIni)
                    ->where('rombel_id', $peserta->kelompok_belajar_id)
                    ->orderBy('jam_mulai')
                    ->get();
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

            // 3. Ujian CBT Aktif (Simple active query)
            $data['ujian_aktif'] = CbtUjian::aktif()
                ->orderBy('waktu_mulai', 'asc')
                ->take(3)
                ->get(['id', 'judul', 'waktu_mulai', 'waktu_selesai', 'durasi']);

            $data['role_view'] = 'siswa';
        } else {
            // Assume Guru or Admin (Focus on Guru)
            $data['jadwal_hari_ini'] = Jadwal::with(['mataPelajaran', 'rombel'])
                ->where('hari', $hariIni)
                ->where('guru_id', $user->id)
                ->orderBy('jam_mulai')
                ->get();

            $data['role_view'] = 'guru';
        }

        // Cache response for 5 minutes (300 seconds) to save mobile data
        return $this->successResponse($data, 'Beranda berhasil dimuat')->header('Cache-Control', 'private, max-age=300');
    }
}
