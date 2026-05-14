<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Traits\HandlesImageUpload;

class ProfileController extends Controller
{
    use HandlesImageUpload;
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

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            ], [
                'photo.image' => 'File yang diunggah harus berupa gambar.',
                'photo.mimes' => 'Format gambar hanya diperbolehkan: JPG, JPEG, PNG, WEBP.',
                'photo.max'   => 'Ukuran gambar maksimal adalah 5MB.',
            ]);

            try {
                // Delete old photo if exists
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $path = $this->compressAndStore($request->file('photo'), 'profile-photos');
                $user->photo = $path;
                $user->save();

                return back()->with('success', 'Foto profil berhasil diperbarui!');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Profile Photo Update Error: ' . $e->getMessage());
                return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui foto profil.');
            }
        }

        // Handle photo removal
        if ($request->has('remove_photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
            $user->save();

            return back()->with('success', 'Foto profil berhasil dihapus!');
        }

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
        try {
            $user->save();
            return back()->with('success', $pesan);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Profile Update Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan perubahan profil.');
        }
    }
}
