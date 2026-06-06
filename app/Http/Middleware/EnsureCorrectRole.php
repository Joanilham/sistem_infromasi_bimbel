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
        'super admin'   => 'dashboard',
        'admin'         => 'dashboard',
        'administrator' => 'dashboard',
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

        // Normalisasi allowed roles (case-insensitive)
        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $single) {
                // Support mapping old names to new standard names
                $name = strtolower(trim($single));
                if ($name === 'administrator') $allowedRoles[] = 'super admin';
                $allowedRoles[] = $name;
            }
        }

        $userRoleLower = strtolower($user->level);

        // Jika role user tidak ada di daftar yang diizinkan
        if (!in_array($userRoleLower, $allowedRoles, true)) {
            // Redirect ke dashboard sesuai role mereka
            $redirectRoute = $this->roleRedirectMap[$userRoleLower] ?? 'dashboard';

            \Illuminate\Support\Facades\Log::info('EnsureCorrectRole Blocked:', [
                'user_role' => $userRoleLower,
                'allowed' => $allowedRoles,
                'route' => $request->route()->getName(),
            ]);

            return redirect()
                ->route($redirectRoute)
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
