<?php

namespace App\Http\Controllers\Admin;
use App\Models\Akademik\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RekapitulasiService;
use App\Exports\RekapitulasiExport;

class RekapitulasiController extends Controller
{
    public function __construct(
        protected RekapitulasiService $rekapitulasiService,
        protected RekapitulasiExport $rekapitulasiExport
    ) {}

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'siswa');
        
        $data = ['tab' => $tab];

        if ($tab === 'siswa') {
            $data = array_merge($data, $this->rekapitulasiService->getSiswaData($request));
        } elseif ($tab === 'guru') {
            $data = array_merge($data, $this->rekapitulasiService->getGuruData($request));
        } elseif ($tab === 'keuangan') {
            $data = array_merge($data, $this->rekapitulasiService->getKeuanganData($request));
        } elseif ($tab === 'absensi') {
            $data = array_merge($data, $this->rekapitulasiService->getAbsensiData($request));
        }

        return view('admin.rekapitulasi.index', $data);
    }

    public function export(Request $request)
    {
        return $this->rekapitulasiExport->generate($request);
    }

    public function exportKustom(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:peserta_didik,pembayaran,absensi,keuangan_operasional,keuangan_semua',
            'rentang' => 'nullable|in:hari_ini,minggu_ini,bulan_ini,semua_waktu',
            'tanggal_awal' => 'nullable|required_without:rentang|date',
            'tanggal_akhir' => 'nullable|required_without:rentang|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        if ($request->rentang === 'hari_ini') {
            $tanggalAwal = \Carbon\Carbon::today()->format('Y-m-d');
            $tanggalAkhir = \Carbon\Carbon::today()->format('Y-m-d');
        } elseif ($request->rentang === 'minggu_ini') {
            $tanggalAwal = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d');
            $tanggalAkhir = \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d');
        } elseif ($request->rentang === 'bulan_ini') {
            $tanggalAwal = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
            $tanggalAkhir = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($request->rentang === 'semua_waktu') {
            $tanggalAwal = '1970-01-01';
            $tanggalAkhir = \Carbon\Carbon::now()->addYears(100)->format('Y-m-d');
        }

        if ($request->jenis_laporan === 'peserta_didik') {
            return (new \App\Exports\LaporanPesertaDidikKustomExport)->export($tanggalAwal, $tanggalAkhir);
        } elseif ($request->jenis_laporan === 'pembayaran') {
            return (new \App\Exports\LaporanPembayaranKustomExport)->export($tanggalAwal, $tanggalAkhir);
        } elseif ($request->jenis_laporan === 'absensi' || in_array($request->jenis_laporan, ['keuangan_operasional', 'keuangan_semua'])) {
            $req = new \Illuminate\Http\Request();
            $tab = $request->jenis_laporan === 'absensi' ? 'absensi' : 'keuangan';
            $req->merge([
                'tab' => $tab,
                'start_date' => $tanggalAwal,
                'end_date' => $tanggalAkhir,
                'jenis_keuangan' => $request->jenis_laporan, // untuk filter di RekapitulasiExport
            ]);
            // Re-bind session as new request won't have it natively
            $req->setLaravelSession($request->session());
            return $this->rekapitulasiExport->generate($req);
        }
        
        return back()->with('error', 'Jenis laporan tidak valid.');
    }
}

