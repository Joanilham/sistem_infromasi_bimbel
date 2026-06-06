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
}

