<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check() || !auth()->user()->hasPermission($permission)) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Akses ditolak. Anda tidak memiliki hak akses untuk fitur ini.']);
        }

        return $next($request);
    }
}
