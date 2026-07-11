<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (strpos(config('app.url'), 'https://') === 0) {
            URL::forceScheme('https');
            $request->server->set('HTTPS', 'on');
            $request->headers->set('X-Forwarded-Proto', 'https', true);
        }

        return $next($request);
    }
}
