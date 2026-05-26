<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $userExists = \App\Models\User::where('email', $request->email)->exists();
        $pendaftaran = \App\Models\PendaftaranSiswa::where('email', $request->email)->first();

        if (!$userExists && !$pendaftaran) {
            return back()->withErrors([
                'email' => 'Email Anda belum terdaftar. Silakan mendaftar terlebih dahulu melalui menu Pendaftaran.',
            ])->onlyInput('email');
        }

        if ($pendaftaran) {
            // Kita hanya perlu mencegah login jika pendaftaran DITOLAK MUTLAK oleh admin
            if ($pendaftaran->status === 'ditolak') {
                $catatan = $pendaftaran->catatan_admin
                    ? ' Catatan admin: ' . $pendaftaran->catatan_admin
                    : ' Hubungi administrator untuk informasi lebih lanjut.';
                return back()->withErrors([
                    'email' => 'Pendaftaran Anda ditolak.' . $catatan,
                ])->onlyInput('email');
            }
        }

        // Ambil hanya email + password untuk Auth::attempt
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            // Pengecekan is_active (Banned)
            if (!Auth::user()->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ])->onlyInput('email');
            }

            // ── Batasan Sesi: Maks 2 Perangkat Sekaligus (OPSI B: BLOKIR KERAS) ──
            // Hitung sesi aktif milik user ini, KECUALI sesi yang baru saja dibuat.
            $userId = Auth::id();
            $currentSessionId = session()->getId();
            $sessionLifetimeSeconds = config('session.lifetime') * 60; // Default 15 menit

            $activeSessionCount = \Illuminate\Support\Facades\DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $currentSessionId) // Mengecualikan sesi percobaan login ini
                ->where('last_activity', '>=', now()->timestamp - $sessionLifetimeSeconds)
                ->count();

            // Jika sudah ada 2 sesi AKTIF di perangkat lain, tolak login ini
            if ($activeSessionCount >= 2) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun ini sedang aktif di 2 perangkat lain. Silakan logout dari perangkat lain, atau tunggu 15 menit jika Anda lupa logout.',
                ])->onlyInput('email');
            }
            // ──────────────────────────────────────────────────────────────────

            $request->session()->regenerate();

            $user = Auth::user();
            $level = strtolower($user->level);

            if (in_array($level, ['super admin', 'admin'])) {
                // Otomatis atur default kantor_id & periode_id ke session
                $kantorId = $user->kantor_id ?: (\App\Models\Kantor::first()->id ?? null);
                $periodeId = $user->periode_id ?: (\App\Models\Periode::where('is_active', true)->first()->id ?? (\App\Models\Periode::first()->id ?? null));

                if ($kantorId) {
                    $request->session()->put('kantor_id', $kantorId);
                }
                if ($periodeId) {
                    $request->session()->put('periode_id', $periodeId);
                }

                return redirect()->route('konteks.select');
            }

            // Redirect khusus untuk guru
            if ($level === 'guru') {
                return redirect()->route('guru.dashboard');
            }

            // Redirect khusus untuk siswa
            if ($level === 'siswa') {
                return redirect()->route('siswa.dashboard');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        // Log out specifically from the web guard
        \Illuminate\Support\Facades\Auth::guard('web')->logout();

        // Clear all session data completely
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to welcome with explicit headers to prevent browser caching
        return redirect()->route('welcome')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => 'Sun, 02 Jan 1990 00:00:00 GMT',
        ]);
    }
}