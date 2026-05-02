<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: SecurityHeaders
 *
 * Menghapus semua HTTP header yang mengungkap teknologi/framework
 * yang digunakan, dan menambahkan header keamanan standar industri.
 */
class SecurityHeaders
{
    /**
     * Header yang harus DIHAPUS agar tidak mengungkap teknologi.
     */
    protected array $removeHeaders = [
        'X-Powered-By',
        'Server',
        'X-Generator',
        'X-Framework',
        'X-Runtime',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hapus header yang mengungkap teknologi
        foreach ($this->removeHeaders as $header) {
            $response->headers->remove($header);
        }

        // Tambahkan security headers standar
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // Izinkan kamera & mikrofon untuk fitur absensi QR
        $response->headers->set('Permissions-Policy', 'camera=*, microphone=*, geolocation=()');



        return $response;
    }
}
