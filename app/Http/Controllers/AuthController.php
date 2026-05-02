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

            // Redirect ke halaman pilih konteks khusus untuk admin / staff jika belum memilih
            $user = Auth::user();
            if (in_array($user->level, ['administrator', 'staff'])) {
                if (!$request->session()->has('kantor_id') || !$request->session()->has('periode_id')) {
                    return redirect()->route('konteks.select');
                }
                return redirect()->intended('dashboard');
            }

            // Redirect khusus untuk guru
            if ($user->level === 'guru') {
                return redirect()->route('guru.dashboard');
            }

            // Redirect khusus untuk siswa
            if ($user->level === 'siswa') {
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
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
