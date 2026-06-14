<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\System\AuditLog;
use App\Models\Akademik\PesertaDidik;
use App\Models\Keuangan\TransaksiPembayaran;
use App\Models\Keuangan\Pemasukan;
use App\Models\Keuangan\Pengeluaran;
use App\Models\CBT\CbtUjian;
use App\Models\MasterData\Periode;
use App\Models\MasterData\Kantor;

class RecycleBinController extends Controller
{
    /**
     * Display a listing of trashed resources.
     */
    public function index()
    {
        // Fitur akses recycle bin eksklusif
        if (strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses Recycle Bin.');
        }

        $trashedData = [
            'peserta_didik' => PesertaDidik::onlyTrashed()->get(),
            'transaksi' => TransaksiPembayaran::onlyTrashed()->with('pembayaranSiswa.pesertaDidik')->get(),
            'pemasukan' => Pemasukan::onlyTrashed()->get(),
            'pengeluaran' => Pengeluaran::onlyTrashed()->get(),
            'ujian' => CbtUjian::onlyTrashed()->get(),
            'periode' => Periode::onlyTrashed()->get(),
            'kantor' => Kantor::onlyTrashed()->get(),
        ];

        return view('admin.recycle-bin.index', compact('trashedData'));
    }

    /**
     * Restore the specified resource.
     */
    public function restore($type, $id)
    {
        if (strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin.');
        }

        $model = $this->getModelByType($type);
        if (!$model) {
            abort(404, 'Tipe modul tidak valid.');
        }

        $record = $model::onlyTrashed()->findOrFail($id);
        $record->restore();

        // Logging manual jika tidak ditangani otomatis oleh Auditable
        AuditLog::logSystemEvent(
            'Restore Data dari Recycle Bin', 
            class_basename($model), 
            ['id' => $id, 'status' => 'deleted'], 
            ['id' => $id, 'status' => 'restored']
        );

        return back()->with('success', 'Data berhasil dikembalikan dari Recycle Bin.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete($type, $id)
    {
        if (strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin.');
        }

        $model = $this->getModelByType($type);
        if (!$model) {
            abort(404, 'Tipe modul tidak valid.');
        }

        $record = $model::onlyTrashed()->findOrFail($id);
        
        // Log before deleting permanently
        AuditLog::logSystemEvent(
            'Hapus Permanen Data (Force Delete)', 
            class_basename($model), 
            ['id' => $id, 'action' => 'force_delete'], 
            null
        );

        $record->forceDelete();

        return back()->with('success', 'Data telah dihapus secara permanen.');
    }

    /**
     * Helper to map URL type to actual Model Class
     */
    private function getModelByType($type)
    {
        $map = [
            'peserta_didik' => PesertaDidik::class,
            'transaksi' => TransaksiPembayaran::class,
            'pemasukan' => Pemasukan::class,
            'pengeluaran' => Pengeluaran::class,
            'ujian' => CbtUjian::class,
            'periode' => Periode::class,
            'kantor' => Kantor::class,
        ];

        return $map[$type] ?? null;
    }
}
