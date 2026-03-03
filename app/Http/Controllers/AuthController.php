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
            'kantor'   => ['required', 'integer', 'exists:kantors,id'],
            'periode'  => ['required', 'integer', 'exists:periodes,id'],
        ], [
            'kantor.required'  => 'Pilih kantor terlebih dahulu sebelum login.',
            'kantor.exists'    => 'Kantor yang dipilih tidak valid.',
            'periode.required' => 'Pilih periode terlebih dahulu sebelum login.',
            'periode.exists'   => 'Periode yang dipilih tidak valid.',
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

            // Simpan pilihan kantor & periode ke session
            if ($request->filled('kantor')) {
                $request->session()->put('kantor_id', $request->kantor);
            }
            if ($request->filled('periode')) {
                $request->session()->put('periode_id', $request->periode);
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
