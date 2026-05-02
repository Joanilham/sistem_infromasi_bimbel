<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan form lupa sandi.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim email reset sandi.
     */
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Kita bisa batasi hanya untuk guru dan siswa jika mau, tapi standard Laravel reset bagus untuk semua.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Tampilkan form buat sandi baru.
     */
    public function edit(Request $request, $token)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Simpan sandi baru.
     */
    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', 'Kata sandi berhasil diatur ulang. Silakan masuk.')
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
