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

        return back()->with('success', 'Data berhasil dikembalikan dari Recycle Bin.')->with('active_tab', $type);
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

        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

            // Khusus Peserta Didik, bersihkan juga data turunannya agar tidak jadi orphan
            if ($type === 'peserta_didik') {
                $user = \App\Models\User::where('peserta_didik_id', $record->id)->first();
                if ($user) {
                    $cbtPesertaIds = \Illuminate\Support\Facades\DB::table('cbt_pesertas')->where('user_id', $user->id)->pluck('id');
                    if ($cbtPesertaIds->isNotEmpty()) {
                        \Illuminate\Support\Facades\DB::table('cbt_peserta_jawabans')->whereIn('cbt_peserta_id', $cbtPesertaIds)->delete();
                        \Illuminate\Support\Facades\DB::table('cbt_pesertas')->where('user_id', $user->id)->delete();
                    }
                    $conversationIds = \Illuminate\Support\Facades\DB::table('conversation_user')->where('user_id', $user->id)->pluck('conversation_id');
                    if ($conversationIds->isNotEmpty()) {
                        \Illuminate\Support\Facades\DB::table('messages')->whereIn('conversation_id', $conversationIds)->delete();
                        \Illuminate\Support\Facades\DB::table('conversation_user')->whereIn('conversation_id', $conversationIds)->delete();
                        \Illuminate\Support\Facades\DB::table('conversations')->whereIn('id', $conversationIds)->delete();
                    }
                    $user->delete();
                }

                $pembayaranList = \App\Models\Keuangan\PembayaranSiswa::withTrashed()->where('peserta_didik_id', $record->id)->get();
                foreach ($pembayaranList as $pembayaran) {
                    \App\Models\Keuangan\TransaksiPembayaran::withTrashed()->where('pembayaran_siswa_id', $pembayaran->id)->forceDelete();
                    $pembayaran->forceDelete();
                }
                \Illuminate\Support\Facades\DB::table('absensis')->where('peserta_didik_id', $record->id)->delete();
            }

            $record->forceDelete();
            
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            if ($e->getCode() == 23000) {
                return back()->with('error', 'Gagal dihapus permanen! Data ini masih terkait dengan data lain di sistem.');
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return back()->with('error', 'Gagal menghapus secara permanen: ' . $e->getMessage());
        }

        return back()->with('success', 'Data telah dihapus secara permanen.')->with('active_tab', $type);
    }

    /**
     * Bulk Action (Restore/Force Delete multiple items)
     */
    public function bulkAction(Request $request)
    {
        if (strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin.');
        }

        $request->validate([
            'type' => 'required|string',
            'action' => 'required|in:restore,force_delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer',
        ]);

        $type = $request->type;
        $action = $request->action;
        $ids = $request->ids;

        $model = $this->getModelByType($type);
        if (!$model) {
            return back()->with('error', 'Tipe modul tidak valid.')->with('active_tab', $type);
        }

        $count = 0;
        try {
            if ($action === 'restore') {
                $records = $model::onlyTrashed()->whereIn('id', $ids)->get();
                foreach ($records as $record) {
                    $record->restore();
                    $count++;
                }
                AuditLog::logSystemEvent('Bulk Restore Recycle Bin', class_basename($model), null, ['restored_count' => $count]);
                return back()->with('success', "$count Data berhasil dikembalikan dari Recycle Bin.")->with('active_tab', $type);
            } 
            
            if ($action === 'force_delete') {
                // To avoid complex relationships blocking, we iterate and use the forceDelete logic
                // But for efficiency, if we have custom logic in forceDelete, we should re-use it.
                // Since this is a bulk action, let's call the forceDelete method manually for each ID to ensure relationships are cleared properly.
                
                $records = $model::onlyTrashed()->whereIn('id', $ids)->get();
                
                \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
                foreach ($records as $record) {
                    if ($type === 'peserta_didik') {
                        $user = \App\Models\User::where('peserta_didik_id', $record->id)->first();
                        if ($user) {
                            $cbtPesertaIds = \Illuminate\Support\Facades\DB::table('cbt_pesertas')->where('user_id', $user->id)->pluck('id');
                            if ($cbtPesertaIds->isNotEmpty()) {
                                \Illuminate\Support\Facades\DB::table('cbt_peserta_jawabans')->whereIn('cbt_peserta_id', $cbtPesertaIds)->delete();
                                \Illuminate\Support\Facades\DB::table('cbt_pesertas')->where('user_id', $user->id)->delete();
                            }
                            $conversationIds = \Illuminate\Support\Facades\DB::table('conversation_user')->where('user_id', $user->id)->pluck('conversation_id');
                            if ($conversationIds->isNotEmpty()) {
                                \Illuminate\Support\Facades\DB::table('messages')->whereIn('conversation_id', $conversationIds)->delete();
                                \Illuminate\Support\Facades\DB::table('conversation_user')->whereIn('conversation_id', $conversationIds)->delete();
                                \Illuminate\Support\Facades\DB::table('conversations')->whereIn('id', $conversationIds)->delete();
                            }
                            $user->delete();
                        }
        
                        $pembayaranList = \App\Models\Keuangan\PembayaranSiswa::withTrashed()->where('peserta_didik_id', $record->id)->get();
                        foreach ($pembayaranList as $pembayaran) {
                            \App\Models\Keuangan\TransaksiPembayaran::withTrashed()->where('pembayaran_siswa_id', $pembayaran->id)->forceDelete();
                            $pembayaran->forceDelete();
                        }
                        \Illuminate\Support\Facades\DB::table('absensis')->where('peserta_didik_id', $record->id)->delete();
                    }
        
                    $record->forceDelete();
                    $count++;
                }
                \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

                AuditLog::logSystemEvent('Bulk Force Delete Recycle Bin', class_basename($model), null, ['deleted_count' => $count]);
                return back()->with('success', "$count Data telah dihapus secara permanen.")->with('active_tab', $type);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return back()->with('error', 'Terjadi kesalahan saat memproses bulk action: ' . $e->getMessage())->with('active_tab', $type);
        }

        return back()->with('active_tab', $type);
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
