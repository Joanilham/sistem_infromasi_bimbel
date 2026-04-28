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
        $response->headers->set('Permissions-Policy', 'camera=*, microphone=*');

        // Izinkan embedding & pop-up (penting untuk HTTPS + ngrok)
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');

        // Izinkan getUserMedia dari iframe (jika ada)
        $response->headers->set('Feature-Policy', "camera 'self'; microphone 'self'");

        return $response;
    }
}
