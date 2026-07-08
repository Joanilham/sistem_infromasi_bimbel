<?php

namespace App\Http\Middleware;
use App\Models\Akademik\Absensi;

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

        // Hapus header yang mengungkap teknologi (gunakan method header bag jika memungkinkan)
        $headersToRemove = $this->removeHeaders;
        if (property_exists($response, 'headers') && is_object($response->headers)) {
            foreach ($headersToRemove as $header) {
                $response->headers->remove($header);
            }
        }

        // Tambahkan security headers standar yang kompatibel dengan semua tipe response (termasuk StreamedResponse)
        if (property_exists($response, 'headers') && is_object($response->headers)) {
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'camera=*, microphone=*, geolocation=()');
        }



        return $response;
    }
}

