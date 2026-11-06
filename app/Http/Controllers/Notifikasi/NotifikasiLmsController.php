<?php

namespace App\Http\Controllers\Notifikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class NotifikasiLmsController extends Controller
{
    /**
     * Hitung notifikasi belum dibaca untuk user yang sedang login.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !Schema::hasTable('notifications')) {
            return response()->json(['unread_count' => 0]);
        }

        $count = $user->unreadNotifications()->count();

        return response()->json([
            'unread_count' => $count,
        ]);
    }

    /**
     * Ambil daftar notifikasi terbaru (untuk dropdown notifikasi).
     */
    public function recent(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !Schema::hasTable('notifications')) {
            return response()->json([
                'unread_count' => 0,
                'notifications' => [],
            ]);
        }

        $notifications = $user->notifications()
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($notif) {
                $data = $notif->data ?? [];
                return [
                    'id'          => $notif->id,
                    'is_read'     => $notif->read_at !== null,
                    'type'        => $data['type'] ?? 'info',
                    'title'       => $data['title'] ?? 'Pemberitahuan Baru',
                    'message'     => $data['message'] ?? '',
                    'url'         => route('notifikasi.lms.baca', $notif->id, false),
                    'icon'        => $data['icon'] ?? 'bell',
                    'accent'      => $data['accent'] ?? 'indigo',
                    'due_at'      => $data['due_at'] ?? null,
                    'time_ago'    => $notif->created_at?->diffForHumans() ?? 'Baru saja',
                ];
            });

        return response()->json([
            'unread_count'  => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Tandai notifikasi sebagai dibaca dan arahkan ke target URL.
     */
    public function baca(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        if (!$user || !Schema::hasTable('notifications')) {
            return redirect()->back();
        }

        $notification = $user->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            $targetUrl = $notification->data['url'] ?? null;

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'target_url' => $targetUrl]);
            }

            if (!empty($targetUrl)) {
                return redirect()->to($targetUrl);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Tandai semua notifikasi user sebagai telah dibaca.
     */
    public function tandaiSemua(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        if ($user && Schema::hasTable('notifications')) {
            $user->unreadNotifications->markAsRead();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi telah ditandai sebagai dibaca.',
            ]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Halaman pusat arsip seluruh notifikasi dengan filter dan pencarian.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $notifications = collect();
        $filter = $request->input('filter', 'all');
        $search = trim((string) $request->input('search', ''));

        $counts = [
            'all' => 0,
            'unread' => 0,
            'tugas_baru' => 0,
            'tugas_dikumpulkan' => 0,
            'tugas_dinilai' => 0,
        ];

        if ($user && Schema::hasTable('notifications')) {
            $baseQuery = $user->notifications();

            // Hitung badge counter setiap kategori
            $counts['all'] = (clone $baseQuery)->count();
            $counts['unread'] = (clone $baseQuery)->whereNull('read_at')->count();
            $counts['tugas_baru'] = (clone $baseQuery)->where('data->type', 'tugas_baru')->count();
            $counts['tugas_dikumpulkan'] = (clone $baseQuery)->where('data->type', 'tugas_dikumpulkan')->count();
            $counts['tugas_dinilai'] = (clone $baseQuery)->where('data->type', 'tugas_dinilai')->count();

            $query = (clone $baseQuery)->latest();

            // Filter status / kategori
            match ($filter) {
                'unread' => $query->whereNull('read_at'),
                'tugas_baru' => $query->where('data->type', 'tugas_baru'),
                'tugas_dikumpulkan' => $query->where('data->type', 'tugas_dikumpulkan'),
                'tugas_dinilai' => $query->where('data->type', 'tugas_dinilai'),
                default => null,
            };

            // Filter pencarian
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('data->title', 'like', "%{$search}%")
                      ->orWhere('data->message', 'like', "%{$search}%")
                      ->orWhere('data->subject', 'like', "%{$search}%");
                });
            }

            $notifications = $query->paginate(12)->withQueryString();
        }

        $level = strtolower($user->level ?? '');
        $layout = match ($level) {
            'guru' => 'layouts.guru',
            'siswa' => 'layouts.siswa',
            default => 'layouts.admin',
        };

        return view('notifikasi.index', compact('notifications', 'layout', 'filter', 'search', 'counts'));
    }
}
