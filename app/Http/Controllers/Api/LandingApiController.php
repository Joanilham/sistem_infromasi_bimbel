<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Master;
use App\Models\Akademik\PaketBimbingan;

class LandingApiController extends Controller
{
    public function index()
    {
        $master = Master::first();
        $pakets = PaketBimbingan::where('is_featured', true)->orderBy('urutan')->get();
        if ($pakets->isEmpty()) {
            $pakets = PaketBimbingan::orderBy('urutan')->limit(3)->get();
        }

        $baseUrl = url('/');

        return response()->json([
            'success' => true,
            'data' => [
                'nama_lembaga'    => $master->nama_lembaga ?? 'Genius Education',
                'alamat_lembaga'  => $master->alamat_lembaga ?? '',
                'wa_number'       => $master->wa_number ?? '',
                'instagram_url'   => $master->instagram_url ?? null,
                'tentang_kami'    => $master->tentang_kami ?? 'Kami adalah institusi pendidikan yang berdedikasi tinggi dalam menyediakan bimbingan belajar berkualitas.',
                'hero_title'      => $master->hero_title ?? 'Wujudkan Impian Akademik Bersama Kami',
                'hero_subtitle'   => $master->hero_subtitle ?? 'Platform pembelajaran terintegrasi yang memudahkan manajemen pendaftaran, progres belajar, dan evaluasi hasil belajar.',
                'hero_image_url'  => $master->hero_image ? $baseUrl . '/storage/' . $master->hero_image : null,
                'logo_url'        => $master->logo ? $baseUrl . '/storage/' . $master->logo : null,
                'pakets'          => $pakets->map(function ($p) use ($baseUrl) {
                    $benefits = array_values(array_filter(explode("\n", str_replace("\r", "", $p->benefits ?? ''))));
                    return [
                        'id'              => $p->id,
                        'nama_paket'      => $p->nama_paket,
                        'target_peserta'  => $p->target_peserta ?? 'Semua Jenjang',
                        'nominal'         => (int) $p->nominal,
                        'harga_coret'     => $p->harga_coret ? (int) $p->harga_coret : null,
                        'durasi_jumlah'   => $p->durasi_jumlah,
                        'durasi_satuan'   => $p->durasi_satuan,
                        'label_populer'   => $p->label_populer,
                        'benefits'        => $benefits,
                        'gambar_url'      => $p->gambar_paket ? $baseUrl . '/storage/' . $p->gambar_paket : null,
                    ];
                })->values(),
            ]
        ]);
    }
}

