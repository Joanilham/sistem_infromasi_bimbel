<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = strtolower($request->header('User-Agent', ''));
        
        $isPostman = str_contains($userAgent, 'postman');
        $isDart = str_contains($userAgent, 'dart'); // Default User-Agent dari Dio/Flutter
        $isMobileApp = $request->header('X-App-Access') === 'true'; // Custom header tambahan
        
        if (!$isPostman && !$isDart && !$isMobileApp) {
            abort(404);
        }

        return $next($request);
    }
}
