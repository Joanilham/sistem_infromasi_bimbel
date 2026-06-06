<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    use \App\Traits\ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginType, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Kredensial tidak valid.', 401);
        }

        if (!$user->is_active) {
            return $this->errorResponse('Akun Anda tidak aktif.', 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return $this->successResponse([
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'level' => $user->level,
            ],
            'token' => $token,
        ], 'Login berhasil');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout berhasil');
    }

    public function user(Request $request)
    {
        $user = $request->user();

        // Jika siswa, ambil juga data terbaru dari tabel peserta_didik
        if ($user->level === 'Siswa' && $user->peserta_didik_id) {
            $peserta = \App\Models\Akademik\PesertaDidik::with('pembayaran.transaksi')->find($user->peserta_didik_id);
            if ($peserta) {
                $user->no_telp = $peserta->no_telepon ?? $user->no_telp;
                $user->alamat = $peserta->alamat_lengkap ?? $user->alamat;
                $user->name = $peserta->nama_lengkap ?? $user->name;
                $user->nis = $peserta->nisn ?? $peserta->nomor_induk;
                
                $user->dispensasi = false;
                $user->is_overdue = false;

                if ($peserta->pembayaran) {
                    $user->dispensasi = (bool) $peserta->pembayaran->dispensasi;
                    if (!$user->dispensasi && $peserta->pembayaran->kekurangan > 0 && $peserta->pembayaran->batas_waktu) {
                        if (now()->startOfDay()->greaterThan($peserta->pembayaran->batas_waktu)) {
                            $user->is_overdue = true;
                        }
                    }
                }
            }
        }

        return $this->successResponse($user);
    }
}

