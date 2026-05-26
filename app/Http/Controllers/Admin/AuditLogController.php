<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
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

        $query = AuditLog::with('user');

        // Batasan Akses: Super Admin bisa melihat semua, Admin hanya bisa melihat log miliknya sendiri
        if (strtolower(auth()->user()->level) !== 'super admin') {
            $query->where('user_id', auth()->id());
        }

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
        $baseQuery = AuditLog::query();
        if (strtolower(auth()->user()->level) !== 'super admin') {
            $baseQuery->where('user_id', auth()->id());
        }

        $cacheKey = 'audit_log_stats_' . auth()->id();
        $stats = cache()->remember($cacheKey, now()->addMinutes(5), function () use ($baseQuery) {
            $counts = (clone $baseQuery)
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
        // Fitur hapus log adalah hak eksklusif Super Admin demi kepatuhan audit (audit compliance)
        if (strtolower(auth()->user()->level) !== 'super admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus bukti jejak sistem.');
        }

        $log = AuditLog::findOrFail($id);
        $log->delete();
        
        return back()->with('success', 'Log aktivitas berhasil dihapus.');
    }
}
