<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Traits\HandlesImageUpload;
use App\Http\Requests\Auth\UpdateProfilePhotoRequest;
use App\Http\Requests\Auth\UpdateProfilePasswordRequest;
use App\Http\Requests\Auth\UpdateProfileInfoRequest;

class ProfileController extends Controller
{
    use HandlesImageUpload;
    /**
     * Tampilkan form edit profil untuk Administrator & Admin.
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
            $photoReq = app(UpdateProfilePhotoRequest::class);
            return $this->handlePhotoUpload($photoReq, $user);
        }

        // Handle photo removal
        if ($request->has('remove_photo')) {
            return $this->handlePhotoRemoval($user);
        }

        // Handle password or profile update
        if ($request->has('current_password')) {
            $passReq = app(UpdateProfilePasswordRequest::class);
            $pesan = $this->updatePassword($passReq, $user);
        } else {
            $infoReq = app(UpdateProfileInfoRequest::class);
            $pesan = $this->updateProfileInfo($infoReq, $user);
        }

        try {
            $user->save();
            return back()->with('success', $pesan);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Profile Update Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan perubahan profil.');
        }
    }

    // ═══════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════

    private function handlePhotoUpload(UpdateProfilePhotoRequest $request, $user)
    {
        $request->validated();

        try {
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

    private function handlePhotoRemoval($user)
    {
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }
        $user->photo = null;
        $user->save();

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }

    private function updatePassword(UpdateProfilePasswordRequest $request, $user): string
    {
        $validated = $request->validated();

        $user->password = Hash::make($validated['password']);
        return 'Kata sandi berhasil diperbarui!';
    }

    private function updateProfileInfo(UpdateProfileInfoRequest $request, $user): string
    {
        $validated = $request->validated();

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        return 'Informasi profil berhasil diperbarui!';
    }
}


