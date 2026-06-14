<?php

namespace App\Http\Controllers\Keuangan;
use App\Models\Akademik\PesertaDidik;
use App\Models\Akademik\PaketBimbingan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\PembayaranSiswa;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;
        $sort    = in_array($request->input('sort'), ['id', 'nama_lengkap', 'batas_waktu']) ? $request->input('sort') : 'id';
        $order   = in_array($request->input('order'), ['asc', 'desc']) ? $request->input('order') : 'desc';

        // Eager load dan query efisien untuk hindari N+1
        $tagihanQuery = PembayaranSiswa::with(['pesertaDidik.paketBimbingan', 'transaksi'])
            ->withSum(['transaksi' => fn($q) => $q->where('status', 'SUKSES')], 'nominal')
            ->whereHas('pesertaDidik', function ($q) use ($request, $search) {
                $q->inContext()->aktif();
                if ($search) {
                    $q->where(function ($q2) use ($search) {
                        $q2->where('nama_lengkap', 'like', "%{$search}%")
                           ->orWhere('nomor_induk', 'like', "%{$search}%");
                    });
                }
                if ($request->paket_id) {
                    $q->where('paket_id', $request->paket_id);
                }
            })
            ->join('peserta_didiks', 'pembayaran_siswa.peserta_didik_id', '=', 'peserta_didiks.id')
            ->select('pembayaran_siswa.*', 'peserta_didiks.nama_lengkap')
            ->when($sort === 'batas_waktu', function ($q) use ($order) {
                $q->orderByRaw('pembayaran_siswa.batas_waktu IS NULL, pembayaran_siswa.batas_waktu ' . $order);
            })
            ->when($sort !== 'batas_waktu', function ($q) use ($sort, $order) {
                $q->orderBy($sort === 'nama_lengkap' ? 'peserta_didiks.nama_lengkap' : 'pembayaran_siswa.id', $order);
            });

        // Filter hanya yang masih punya kekurangan (dilakukan di DB)
        $tagihanQuery->whereRaw('pembayaran_siswa.total_harus_dibayar > (SELECT IFNULL(SUM(nominal), 0) FROM transaksi_pembayaran WHERE transaksi_pembayaran.pembayaran_siswa_id = pembayaran_siswa.id AND transaksi_pembayaran.status = ? AND transaksi_pembayaran.deleted_at IS NULL)', ['SUKSES']);

        $tagihan = $tagihanQuery->paginate($perPage)->withQueryString();

        $pakets = \App\Models\Akademik\PaketBimbingan::get();
        return view('keuangan.tagihan.index', compact('tagihan', 'search', 'perPage', 'pakets'));
    }
}


