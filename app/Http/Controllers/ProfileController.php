<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil untuk Administrator & Staff.
     */
    public function edit(Request $request)
    {
        return view('admin.profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Tampilkan form edit profil untuk Guru.
     */
    public function editGuru(Request $request)
    {
        return view('guru.profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Tampilkan form edit profil untuk Siswa.
     */
    public function editSiswa(Request $request)
    {
        return view('siswa.profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Update profil (digunakan oleh semua role).
     * Field 'level' TIDAK PERNAH bisa diubah dari form ini.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Cek form mana yang dikirim berdasarkan input yang ada
        if ($request->has('current_password')) {
            // Validasi untuk form ubah kata sandi
            $validated = $request->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password'         => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user->password = Hash::make($validated['password']);
            $pesan = 'Kata sandi berhasil diperbarui!';
        } else {
            // Validasi untuk form informasi profil
            $validated = $request->validate([
                'name'  => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ]);

            $user->name  = $validated['name'];
            $user->email = $validated['email'];
            $pesan = 'Informasi profil berhasil diperbarui!';
        }

        // KEAMANAN: pastikan field sensitif tidak bisa diubah via form
        // 'level' dan 'is_active' tidak pernah disentuh di sini
        $user->save();

        return back()->with('success', $pesan);
    }
}
