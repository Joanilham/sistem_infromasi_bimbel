<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: EnsureCorrectRole
 *
 * Memastikan user yang sudah login HANYA bisa mengakses halaman
 * yang sesuai dengan role (level) mereka.
 * Jika role tidak cocok, user akan diarahkan ke dashboard mereka masing-masing.
 */
class EnsureCorrectRole
{
    /**
     * Peta redirect berdasarkan role user.
     * Digunakan untuk mengarahkan user ke area yang benar jika mereka
     * mencoba mengakses area yang bukan milik mereka.
     */
    protected array $roleRedirectMap = [
        'administrator' => 'dashboard',
        'staff'         => 'dashboard',
        'guru'          => 'guru.dashboard',
        'siswa'         => 'siswa.dashboard',
    ];

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Jika belum login, lempar ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Normalisasi allowed roles (support "administrator,staff")
        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $single) {
                $allowedRoles[] = trim($single);
            }
        }

        // Jika role user tidak ada di daftar yang diizinkan
        if (!in_array($user->level, $allowedRoles)) {
            // Redirect ke dashboard sesuai role mereka
            $redirectRoute = $this->roleRedirectMap[$user->level] ?? 'dashboard';

            return redirect()
                ->route($redirectRoute)
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
