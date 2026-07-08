<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CameraPermissionHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Izinkan akses kamera/mikrofon dari origin manapun, kompatibel dengan semua tipe response
        if (property_exists($response, 'headers') && is_object($response->headers)) {
            $response->headers->set('Permissions-Policy', 'camera=*, microphone=*');
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
            $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');
            $response->headers->set('Feature-Policy', "camera 'self'; microphone 'self'");
        }

        return $response;
    }
}
