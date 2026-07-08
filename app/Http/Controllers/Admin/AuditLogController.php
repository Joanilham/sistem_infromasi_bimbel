<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $event = $request->input('event');
        $perPage = $request->input('per_page', 10);

        $query = $this->buildAuditBaseQuery(AuditLog::with('user'));

        $logs = $query->when($search, function ($q) use ($search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->whereHas('user', function ($userQ) use ($search) {
                        $userQ->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('auditable_type', 'like', "%{$search}%")
                    ->orWhere('old_values', 'like', "%{$search}%")
                    ->orWhere('new_values', 'like', "%{$search}%");
                });
            })
            ->when($event, function ($q) use ($event) {
                $q->where('event', $event);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $stats = $this->getAuditStats();
        $created = $stats['created'];
        $updated = $stats['updated'];
        $deleted = $stats['deleted'];

        return view('admin.audit-logs.index', compact('logs', 'search', 'event', 'perPage', 'created', 'updated', 'deleted'));
    }

    /**
     * Delete an audit log (optional)
     */
    public function destroy($id)
    {
        // Fitur hapus log adalah hak untuk Super Admin dan Admin
        if (!in_array(strtolower(auth()->user()->level), ['super admin', 'admin', 'administrator'])) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus bukti jejak sistem.');
        }

        $log = AuditLog::findOrFail($id);
        $log->delete();
        
        return back()->with('success', 'Log aktivitas berhasil dihapus.');
    }

    /**
     * Delete audit logs older than 30 days
     */
    public function prune(Request $request)
    {
        // Fitur hapus log adalah hak untuk Super Admin dan Admin
        if (!in_array(strtolower(auth()->user()->level), ['super admin', 'admin', 'administrator'])) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk membersihkan log sistem.');
        }

        $request->validate([
            'password' => 'required|string',
            'periode'  => 'required|in:30_days,all',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Kata sandi tidak sesuai. Autentikasi gagal.');
        }

        $periode = $request->input('periode');
        $deletedCount = 0;
        
        if ($periode === 'all') {
            $deletedCount = AuditLog::count();
            AuditLog::truncate();
            $msg = "Berhasil menghapus seluruh {$deletedCount} log aktivitas.";
            $infoMsg = "Tidak ada log aktivitas untuk dibersihkan.";
        } else {
            $date30DaysAgo = now()->subDays(30);
            $deletedCount = AuditLog::where('created_at', '<', $date30DaysAgo)->delete();
            $msg = "Berhasil membersihkan {$deletedCount} log aktivitas yang lebih lama dari 30 hari.";
            $infoMsg = "Tidak ada log aktivitas yang umurnya lebih dari 30 hari untuk dibersihkan.";
        }

        if ($deletedCount > 0) {
            // Catat aktivitas pembersihan ini sendiri (setelah truncate/delete selesai)
            AuditLog::logSystemEvent(
                'Bersihkan Log Lama', 
                'AuditLog', 
                null, 
                ['jumlah_dihapus' => $deletedCount, 'batas_tanggal' => $periode === 'all' ? 'Semua Waktu' : now()->subDays(30)->format('Y-m-d')]
            );
            return back()->with('success', $msg);
        }

        return back()->with('info', $infoMsg);
    }

    // ═══════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════

    private function buildAuditBaseQuery($query)
    {
        if (strtolower(auth()->user()->level) !== 'super admin') {
            $query->where(function ($q) {
                $q->whereHas('user', function ($u) {
                    $u->whereRaw('LOWER(level) != ?', ['super admin']);
                })->orWhereNull('user_id');
            });
        }
        return $query;
    }

    private function getAuditStats()
    {
        $cacheKey = 'audit_log_stats_' . auth()->id();
        return cache()->remember($cacheKey, now()->addMinutes(5), function () {
            $baseQuery = $this->buildAuditBaseQuery(AuditLog::query());
            $counts = $baseQuery
                ->whereIn('event', ['created', 'updated', 'deleted'])
                ->selectRaw('event, count(*) as count')
                ->groupBy('event')
                ->pluck('count', 'event');

            return [
                'created' => $counts['created'] ?? 0,
                'updated' => $counts['updated'] ?? 0,
                'deleted' => $counts['deleted'] ?? 0,
            ];
        });
    }
}

