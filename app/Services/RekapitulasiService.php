<?php

namespace App\Services;
use App\Models\Keuangan\PembayaranSiswa;
use App\Models\Akademik\PaketBimbingan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Akademik\PesertaDidik;
use App\Models\User;
use App\Models\Keuangan\Pemasukan;
use App\Models\Keuangan\Pengeluaran;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Models\Akademik\Absensi;
use App\Models\Akademik\KelompokBelajar;
use App\Models\Keuangan\KategoriPemasukan;
use App\Models\Keuangan\KategoriPengeluaran;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class RekapitulasiService
{
    public function getSiswaData(Request $request): array
    {
        $data = [];
        $data['filter_kelas'] = KelompokBelajar::inContext()->get();
        $data['selected_kelas'] = $request->query('kelompok_belajar_id');
        $data['selected_gender'] = $request->query('jenis_kelamin');
        $data['selected_status'] = $request->query('status');
        $data['search'] = $request->query('search');

        $siswaQuery = PesertaDidik::inContext();
        
        if ($data['selected_kelas']) {
            $siswaQuery->where('kelompok_belajar_id', $data['selected_kelas']);
        }
        if ($data['selected_gender']) {
            $siswaQuery->where('jenis_kelamin', $data['selected_gender']);
        }
        if ($data['selected_status']) {
            $siswaQuery->where('status', $data['selected_status']);
        }
        if ($data['search']) {
            $siswaQuery->where(function($q) use ($data) {
                $q->where('nama_lengkap', 'like', '%' . $data['search'] . '%')
                  ->orWhere('nisn', 'like', '%' . $data['search'] . '%');
            });
        }
        
        $data['total_aktif'] = (clone $siswaQuery)->aktif()->count();
        $data['total_keluar'] = (clone $siswaQuery)->keluar()->count();
        
        $data['gender_aktif_l'] = (clone $siswaQuery)->aktif()->where('jenis_kelamin', 'L')->count();
        $data['gender_aktif_p'] = (clone $siswaQuery)->aktif()->where('jenis_kelamin', 'P')->count();
        $data['gender_keluar_l'] = (clone $siswaQuery)->keluar()->where('jenis_kelamin', 'L')->count();
        $data['gender_keluar_p'] = (clone $siswaQuery)->keluar()->where('jenis_kelamin', 'P')->count();

        $data['rekap_paket'] = (clone $siswaQuery)->aktif()
            ->select('paket_bimbingan_id', DB::raw('count(*) as total'))
            ->with('paketBimbingan')
            ->groupBy('paket_bimbingan_id')
            ->get();

        $data['rekap_kelas'] = (clone $siswaQuery)->aktif()
            ->select('kelompok_belajar_id', DB::raw('count(*) as total'))
            ->with('kelompokBelajar')
            ->groupBy('kelompok_belajar_id')
            ->get();
            
        $data['list_siswa'] = (clone $siswaQuery)->with(['kelompokBelajar', 'paketBimbingan'])->paginate(10)->withQueryString();

        return $data;
    }

    public function getGuruData(Request $request): array
    {
        $data = [];
        $data['filter_mapel'] = User::inContext()->where('level', 'guru')->whereNotNull('matapelajaran')->where('matapelajaran', '!=', '')->distinct()->pluck('matapelajaran');
        $data['selected_mapel'] = $request->query('matapelajaran');
        $data['selected_gender'] = $request->query('jenis_kelamin');
        $data['selected_status'] = $request->query('status');
        $data['search'] = $request->query('search');

        $guruQuery = User::inContext()->where('level', 'guru');
        
        if ($data['selected_mapel']) {
            $guruQuery->where('matapelajaran', $data['selected_mapel']);
        }
        if ($data['selected_gender']) {
            $guruQuery->where('jenis_kelamin', $data['selected_gender']);
        }
        if ($data['selected_status']) {
            $guruQuery->where('status', $data['selected_status']);
        }
        if ($data['search']) {
            $guruQuery->where('name', 'like', '%' . $data['search'] . '%');
        }
        
        $data['total_aktif'] = (clone $guruQuery)->where('status', 'aktif')->count();
        $data['total_keluar'] = (clone $guruQuery)->where('status', 'keluar')->count();
        
        $data['guru_gender_aktif_l'] = (clone $guruQuery)->where('status', 'aktif')->where('jenis_kelamin', 'Laki-Laki')->count();
        $data['guru_gender_aktif_p'] = (clone $guruQuery)->where('status', 'aktif')->where('jenis_kelamin', 'Perempuan')->count();

        $data['rekap_mapel'] = (clone $guruQuery)->where('status', 'aktif')
            ->select('matapelajaran', DB::raw('count(*) as total'))
            ->groupBy('matapelajaran')
            ->get();
            
        $data['list_guru'] = (clone $guruQuery)->paginate(10)->withQueryString();

        return $data;
    }

    public function getKeuanganData(Request $request): array
    {
        $data = [];
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['search'] = $request->query('search');
        $data['selected_kategori'] = $request->query('kategori_id');
        
        $data['kategori_pemasukan_list'] = KategoriPemasukan::where('nama', '!=', '')->whereNotNull('nama')->get();
        $data['kategori_pengeluaran_list'] = KategoriPengeluaran::where('nama', '!=', '')->whereNotNull('nama')->get();

        $pemasukanLain = Pemasukan::whereBetween('tanggal', [$startDate, $endDate])->sum('nominal');
        $pemasukanSpp = TransaksiPembayaran::whereBetween('tanggal', [$startDate, $endDate])
            ->whereHas('pembayaranSiswa.pesertaDidik', function($q) use ($kantorId, $periodeId) {
                if ($kantorId) $q->where('kantor_id', $kantorId);
                if ($periodeId) $q->where('periode_id', $periodeId);
            })->sum('nominal');
            
        $data['total_pemasukan'] = $pemasukanLain + $pemasukanSpp;
        $data['total_pengeluaran'] = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])->sum('nominal');

        $data['rekap_pengeluaran'] = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])
            ->select('kategori_id', DB::raw('SUM(nominal) as total'))
            ->with('kategori')
            ->groupBy('kategori_id')
            ->get();
            
        $data['rekap_pemasukan_lain'] = Pemasukan::whereBetween('tanggal', [$startDate, $endDate])
            ->select('kategori_id', DB::raw('SUM(nominal) as total'))
            ->with('kategori')
            ->groupBy('kategori_id')
            ->get();
        $data['pemasukan_spp'] = $pemasukanSpp;
        
        $pemasukanLainList = collect([]);
        $pemasukanSppList = collect([]);
        $pengeluaranList = collect([]);

        $qPemasukan = Pemasukan::whereBetween('tanggal', [$startDate, $endDate])->with('kategori');
        if ($data['search']) {
            $qPemasukan->where('keterangan', 'like', '%' . $data['search'] . '%');
        }
        if ($data['selected_kategori'] && str_starts_with($data['selected_kategori'], 'in_')) {
            $qPemasukan->where('kategori_id', str_replace('in_', '', $data['selected_kategori']));
        } elseif ($data['selected_kategori'] && str_starts_with($data['selected_kategori'], 'out_')) {
            $qPemasukan->whereRaw('1 = 0');
        }
        
        $pemasukanLainList = $qPemasukan->get()->map(function($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'Pemasukan Lainnya',
                'kategori' => $item->kategori ? $item->kategori->nama : '-',
                'keterangan' => $item->keterangan ?? '-',
                'nominal' => $item->nominal,
                'tipe' => 'pemasukan'
            ];
        });

        $qSpp = TransaksiPembayaran::whereBetween('tanggal', [$startDate, $endDate])
            ->with(['pembayaranSiswa.pesertaDidik'])
            ->whereHas('pembayaranSiswa.pesertaDidik', function($q) use ($kantorId, $periodeId) {
                if ($kantorId) $q->where('kantor_id', $kantorId);
                if ($periodeId) $q->where('periode_id', $periodeId);
            });
            
        if ($data['search']) {
            $qSpp->whereHas('pembayaranSiswa.pesertaDidik', function($q) use ($data) {
                $q->where('nama_lengkap', 'like', '%' . $data['search'] . '%');
            });
        }
        if ($data['selected_kategori']) {
            if ($data['selected_kategori'] !== 'spp') $qSpp->whereRaw('1 = 0');
        }
        
        $pemasukanSppList = $qSpp->get()->map(function($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'Pembayaran SPP',
                'kategori' => 'SPP/Bimbingan',
                'keterangan' => 'Pembayaran SPP an. ' . ($item->pembayaranSiswa->pesertaDidik->nama_lengkap ?? ''),
                'nominal' => $item->nominal,
                'tipe' => 'pemasukan'
            ];
        });

        $qPengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])->with('kategori');
        if ($data['search']) {
            $qPengeluaran->where('keterangan', 'like', '%' . $data['search'] . '%');
        }
        if ($data['selected_kategori'] && str_starts_with($data['selected_kategori'], 'out_')) {
            $qPengeluaran->where('kategori_id', str_replace('out_', '', $data['selected_kategori']));
        } elseif ($data['selected_kategori'] && (str_starts_with($data['selected_kategori'], 'in_') || $data['selected_kategori'] === 'spp')) {
            $qPengeluaran->whereRaw('1 = 0');
        }
        
        $pengeluaranList = $qPengeluaran->get()->map(function($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'Pengeluaran',
                'kategori' => $item->kategori ? $item->kategori->nama : '-',
                'keterangan' => $item->keterangan ?? '-',
                'nominal' => $item->nominal,
                'tipe' => 'pengeluaran'
            ];
        });

        $allTransaksi = collect([])
            ->concat($pemasukanLainList)
            ->concat($pemasukanSppList)
            ->concat($pengeluaranList)
            ->sortByDesc('tanggal')
            ->values();

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $allTransaksi->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $data['list_keuangan'] = new LengthAwarePaginator($currentPageItems, count($allTransaksi), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'query' => $request->query()
        ]);

        return $data;
    }

    public function getAbsensiData(Request $request): array
    {
        $data = [];
        $kantorId = session('kantor_id');
        $periodeId = session('periode_id');

        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['search'] = $request->query('search');

        $absensiQuery = Absensi::whereBetween('tanggal', [$startDate, $endDate])
            ->whereHas('pesertaDidik', function($q) use ($kantorId, $periodeId, $data) {
                if ($kantorId) $q->where('kantor_id', $kantorId);
                if ($periodeId) $q->where('periode_id', $periodeId);
                if ($data['search']) $q->where('nama_lengkap', 'like', '%' . $data['search'] . '%');
            });

        $data['total_hadir'] = (clone $absensiQuery)->where('status_masuk', 'Hadir')->count();
        $data['total_izin'] = (clone $absensiQuery)->where('status_masuk', 'Izin')->count();
        $data['total_sakit'] = (clone $absensiQuery)->where('status_masuk', 'Sakit')->count();
        $data['total_alpha'] = (clone $absensiQuery)->where('status_masuk', 'Alpha')->count();
        
        $data['total_absensi'] = $data['total_hadir'] + $data['total_izin'] + $data['total_sakit'] + $data['total_alpha'];
        
        $rekapSiswaQuery = PesertaDidik::inContext()
            ->with(['kelompokBelajar', 'paketBimbingan'])
            ->whereHas('absensi', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->withCount([
                'absensi as hadir_count' => function($q) use ($startDate, $endDate) { 
                    $q->whereBetween('tanggal', [$startDate, $endDate])->where('status_masuk', 'Hadir'); 
                },
                'absensi as sakit_count' => function($q) use ($startDate, $endDate) { 
                    $q->whereBetween('tanggal', [$startDate, $endDate])->where('status_masuk', 'Sakit'); 
                },
                'absensi as izin_count' => function($q) use ($startDate, $endDate) { 
                    $q->whereBetween('tanggal', [$startDate, $endDate])->where('status_masuk', 'Izin'); 
                },
                'absensi as alpha_count' => function($q) use ($startDate, $endDate) { 
                    $q->whereBetween('tanggal', [$startDate, $endDate])->where('status_masuk', 'Alpha'); 
                }
            ]);
            
        if ($data['search']) {
            $rekapSiswaQuery->where('nama_lengkap', 'like', '%' . $data['search'] . '%');
        }
        $data['rekap_siswa'] = $rekapSiswaQuery->get();

        return $data;
    }
}


