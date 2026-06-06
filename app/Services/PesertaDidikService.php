<?php

namespace App\Services;
use App\Models\Akademik\PaketBimbingan;
use App\Models\Akademik\KelompokBelajar;

use App\Models\Akademik\PesertaDidik;
use Illuminate\Http\Request;

class PesertaDidikService
{
    public function getAktifData(Request $request)
    {
        $search     = $request->input('search', '');
        $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $paketId    = $request->input('paket_id');
        $kelompokId = $request->input('kelompok_id');
        $jk         = $request->input('jenis_kelamin');
        $sort       = in_array($request->input('sort'), ['id', 'nama_lengkap', 'paket_bimbingan_id', 'kelompok_belajar_id']) ? $request->input('sort') : 'id';
        $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        return PesertaDidik::aktif()
            ->inContext()
            ->with('paketBimbingan', 'kelompokBelajar')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('nisn', 'like', "%{$search}%")
                       ->orWhere('asal_sekolah', 'like', "%{$search}%");
                });
            })
            ->when($paketId, fn($q) => $q->where('paket_bimbingan_id', $paketId))
            ->when($kelompokId, fn($q) => $q->where('kelompok_belajar_id', $kelompokId))
            ->when($jk, fn($q) => $q->where('jenis_kelamin', $jk))
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getKeluarData(Request $request)
    {
        $search     = $request->input('search', '');
        $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $paketId    = $request->input('paket_id');
        $kelompokId = $request->input('kelompok_id');
        $jk         = $request->input('jenis_kelamin');
        $sort       = in_array($request->input('sort'), ['id', 'nama_lengkap', 'tanggal_keluar']) ? $request->input('sort') : 'tanggal_keluar';
        $order      = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        return PesertaDidik::keluar()
            ->inContext()
            ->with(['paketBimbingan', 'kelompokBelajar'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('nisn', 'like', "%{$search}%")
                       ->orWhere('asal_sekolah', 'like', "%{$search}%");
                });
            })
            ->when($paketId, fn($q) => $q->where('paket_bimbingan_id', $paketId))
            ->when($kelompokId, fn($q) => $q->where('kelompok_belajar_id', $kelompokId))
            ->when($jk, fn($q) => $q->where('jenis_kelamin', $jk))
            ->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();
    }
}


