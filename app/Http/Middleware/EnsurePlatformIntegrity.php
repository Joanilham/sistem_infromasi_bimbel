<?php

namespace App\Http\Middleware;

use App\Support\Security\PlatformIntegrity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformIntegrity
{
    /**
     * URL yang diizinkan untuk diakses saat sistem terkunci.
     */
    protected array $exemptRoutes = [
        'system/platform-verify',
        'up',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // 1. Izinkan akses untuk route verifikasi dan health check
        foreach ($this->exemptRoutes as $exempt) {
            if ($request->is($exempt)) {
                return $next($request);
            }
        }

        // 2. Izinkan file statis jika request langsung menuju asset
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|ico)$/i', $request->path())) {
            return $next($request);
        }

        // 3. Verifikasi lisensi platform
        $verification = PlatformIntegrity::verify();

        if (!$verification['valid']) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'locked',
                    'error' => 'Platform License Inactive',
                    'message' => $verification['reason'],
                    'contact' => 'Silakan hubungi administrator pengembang untuk aktivasi lisensi sistem.',
                ], 423);
            }

            return response()->view('errors.license-lock', [
                'reason' => $verification['reason'],
                'payload' => $verification['payload'],
            ], 423);
        }

        return $next($request);
    }
}
