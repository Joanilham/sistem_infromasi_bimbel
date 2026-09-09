<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\Auth\AuthLoginRequest;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     * Cyclomatic Complexity dikurangi dengan ekstrak private helpers.
     */
    public function authenticate(AuthLoginRequest $request)
    {
        $request->validated();

        $loginInput = trim((string) ($request->input('login') ?? $request->input('email')));
        $password   = (string) $request->input('password');

        // Cari user berdasarkan email, username, atau NISN (via pesertaDidik)
        $user = \App\Models\User::where(function ($query) use ($loginInput) {
            $query->where('email', $loginInput)
                  ->orWhere('username', $loginInput)
                  ->orWhereHas('pesertaDidik', function ($q) use ($loginInput) {
                      $q->where('nisn', $loginInput);
                  });
        })->first();

        // Jika user tidak ditemukan, periksa status pendaftaran siswa
        if (!$user) {
            $pendaftaran = \App\Models\Pendaftaran\PendaftaranSiswa::where('email', $loginInput)->first();
            if ($pendaftaran && $pendaftaran->status === 'ditolak') {
                $catatan = $pendaftaran->catatan_admin
                    ? ' Catatan admin: ' . $pendaftaran->catatan_admin
                    : ' Hubungi administrator untuk informasi lebih lanjut.';
                return back()->withErrors([
                    'login' => 'Pendaftaran Anda ditolak.' . $catatan,
                    'email' => 'Pendaftaran Anda ditolak.' . $catatan,
                ])->onlyInput('login', 'email');
            }

            return back()->withErrors([
                'login' => 'Email, Username, atau NISN belum terdaftar dalam sistem.',
                'email' => 'Email, Username, atau NISN belum terdaftar dalam sistem.',
            ])->onlyInput('login', 'email');
        }

        // Lakukan autentikasi menggunakan email user yang ditemukan
        if (!Auth::attempt(['email' => $user->email, 'password' => $password], $request->boolean('remember'))) {
            return back()->withErrors([
                'login' => 'Kata sandi yang Anda masukkan salah.',
                'email' => 'Kata sandi yang Anda masukkan salah.',
            ])->onlyInput('login', 'email');
        }

        // Cek akun dinonaktifkan (Banned)
        if (!Auth::user()->is_active) {
            $this->forceLogout($request);
            return back()->withErrors([
                'login' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ])->onlyInput('login', 'email');
        }

        // Batasan Sesi: Maks 2 Perangkat Sekaligus
        $sessionError = $this->checkSessionLimit($request);
        if ($sessionError) {
            return back()->withErrors([
                'login' => $sessionError,
                'email' => $sessionError,
            ])->onlyInput('login', 'email');
        }

        $request->session()->regenerate();

        return $this->redirectByLevel($request, Auth::user());
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $recallerName = Auth::guard('web')->getRecallerName();

        // Log out specifically from the web guard
        Auth::guard('web')->logout();

        // Clear all session data completely
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login or welcome (default to login)
        $redirectParam = $request->query('redirect');
        $redirectRoute = $redirectParam === 'welcome' ? 'welcome' : 'login';

        $response = redirect()->route($redirectRoute)->withHeaders([
            'Cache-Control'     => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma'            => 'no-cache',
            'Expires'           => 'Sun, 02 Jan 1990 00:00:00 GMT',
            'Clear-Site-Data'   => '"cache", "storage"',
        ]);

        // Explicitly forget remember cookie and session cookie
        if ($recallerName) {
            $response->withCookie(Cookie::forget($recallerName));
        }
        $sessionCookieName = config('session.cookie', 'nivora-session');
        $response->withCookie(Cookie::forget($sessionCookieName));

        return $response;
    }

    // ═══════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════

    /**
     * Validasi apakah email terdaftar & statusnya valid.
     * Mengembalikan pesan error atau null jika valid.
     */
    private function validateEmailStatus(string $email): ?string
    {
        $userExists  = \App\Models\User::where('email', $email)->exists();
        $pendaftaran = \App\Models\Pendaftaran\PendaftaranSiswa::where('email', $email)->first();

        if (!$userExists && !$pendaftaran) {
            return 'Email Anda belum terdaftar. Silakan mendaftar terlebih dahulu melalui menu Pendaftaran.';
        }

        if ($pendaftaran && $pendaftaran->status === 'ditolak') {
            $catatan = $pendaftaran->catatan_admin
                ? ' Catatan admin: ' . $pendaftaran->catatan_admin
                : ' Hubungi administrator untuk informasi lebih lanjut.';
            return 'Pendaftaran Anda ditolak.' . $catatan;
        }

        return null;
    }

    /**
     * Cek batas sesi aktif (maks 2 perangkat sekaligus).
     * Mengembalikan pesan error jika melewati batas, atau null jika aman.
     */
    private function checkSessionLimit(Request $request): ?string
    {
        $userId                 = Auth::id();
        $currentSessionId       = session()->getId();
        $sessionLifetimeSeconds = config('session.lifetime') * 60;

        $activeSessionCount = DB::table('sessions')
            ->where('user_id', $userId)
            ->where('id', '!=', $currentSessionId)
            ->where('last_activity', '>=', now()->timestamp - $sessionLifetimeSeconds)
            ->count();

        if ($activeSessionCount >= 2) {
            $this->forceLogout($request);
            return 'Akun ini sedang aktif di 2 perangkat lain. Silakan logout dari perangkat lain, atau tunggu 15 menit jika Anda lupa logout.';
        }

        return null;
    }

    /**
     * Redirect user berdasarkan level setelah login berhasil.
     * Admin/Super Admin → set konteks → konteks.select
     * Guru → guru.dashboard
     * Siswa → siswa.dashboard
     */
    private function redirectByLevel(Request $request, \App\Models\User $user)
    {
        $level = strtolower($user->level);

        if (in_array($level, ['super admin', 'admin'])) {
            $kantorId  = $user->kantor_id ?: (\App\Models\MasterData\Kantor::first()->id ?? null);
            $periodeId = $user->periode_id
                ?: (\App\Models\MasterData\Periode::where('is_active', true)->first()->id
                    ?? (\App\Models\MasterData\Periode::first()->id ?? null));

            if ($kantorId)  $request->session()->put('kantor_id', $kantorId);
            if ($periodeId) $request->session()->put('periode_id', $periodeId);

            return redirect()->route('konteks.select');
        }

        return match ($level) {
            'guru'  => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => redirect()->intended('dashboard'),
        };
    }

    /**
     * Paksa logout user saat ini (hapus sesi).
     */
    private function forceLogout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}


