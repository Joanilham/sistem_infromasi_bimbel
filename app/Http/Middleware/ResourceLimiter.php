<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Resource limiter (Basic Tier package limitation)
        // Menunda proses loading selama 3 detik (3000000 microsecond)
        // Hapus atau beri komentar (//) pada baris usleep di bawah ini jika klien sudah membayar lisensi Premium.
        //usleep(3000000);

        return $next($request);
    }
}
