<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            // Jika admin sudah menerima (status = diverifikasi), akun User sudah dibuat
            // → lewati semua pengecekan pendaftaran, biarkan Auth::attempt yang menangani
            if ($pendaftaran->status === 'diverifikasi') {
                // Lanjut ke Auth::attempt di bawah
            }
            // Jika belum diverifikasi email (status masih menunggu & email belum diklik)
            elseif (!$pendaftaran->isEmailVerified()) {
                return back()->withErrors([
                    'email' => '⚠️ Email Anda belum diverifikasi. Silakan cek kotak masuk email dan klik link verifikasi.',
                ])->with('warning_type', 'email_not_verified')
                  ->onlyInput('email');
            }
            // Email sudah diverifikasi siswa tapi admin belum memproses
            elseif ($pendaftaran->status === 'menunggu') {
                return back()->withErrors([
                    'email' => '⏳ Pendaftaran Anda sedang dalam proses verifikasi oleh admin. Mohon tunggu konfirmasi.',
                ])->onlyInput('email');
            }
            // Ditolak admin
            elseif ($pendaftaran->status === 'ditolak') {
                $catatan = $pendaftaran->catatan_admin
                    ? ' Catatan admin: ' . $pendaftaran->catatan_admin
                    : ' Hubungi administrator untuk informasi lebih lanjut.';
                return back()->withErrors([
                    'email' => '❌ Pendaftaran Anda ditolak.' . $catatan,
                ])->onlyInput('email');
            }
        }

        // Ambil hanya email + password untuk Auth::attempt
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Cek apakah akun aktif
            if (!Auth::user()->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
                ])->onlyInput('email');
            }

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
