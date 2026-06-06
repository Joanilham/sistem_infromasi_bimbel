<?php

namespace App\Http\Controllers\Api;
use App\Models\Akademik\PesertaDidik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'no_telp'  => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['no_telp'])) {
            $user->no_telp = $validated['no_telp'];
        }
        if (isset($validated['alamat'])) {
            $user->alamat = $validated['alamat'];
        }
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Sinkronisasi dengan tabel PesertaDidik jika user adalah Siswa
        if ($user->level === 'Siswa' && $user->peserta_didik_id) {
            $peserta = \App\Models\Akademik\PesertaDidik::find($user->peserta_didik_id);
            if ($peserta) {
                if (isset($validated['name'])) {
                    $peserta->nama_lengkap = $validated['name'];
                }
                if (isset($validated['no_telp'])) {
                    $peserta->no_telepon = $validated['no_telp'];
                }
                if (isset($validated['alamat'])) {
                    $peserta->alamat_lengkap = $validated['alamat'];
                }
                $peserta->save();
            }
        }

        return $this->successResponse($user, 'Profil berhasil diperbarui');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
            $user->save();

            return $this->successResponse([
                'photo_url' => asset('storage/' . $path)
            ], 'Foto profil berhasil diperbarui');
        }

        return $this->errorResponse('Gagal mengupload foto', 400);
    }
}


