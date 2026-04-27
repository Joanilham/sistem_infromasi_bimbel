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

        return redirect()->route('login');
    }
}
