<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CameraPermissionHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Izinkan akses kamera/mikrofon dari origin manapun
        $response->withHeaders([
            'Permissions-Policy' => 'camera=*, microphone=*',
            'Cross-Origin-Opener-Policy' => 'same-origin-allow-popups',
            'Cross-Origin-Embedder-Policy' => 'unsafe-none',
            'Feature-Policy' => "camera 'self'; microphone 'self'"
        ]);

        return $response;
    }
}
