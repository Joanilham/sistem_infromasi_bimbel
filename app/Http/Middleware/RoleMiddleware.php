<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Dukung multiple roles: role:administrator,staff
        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $single) {
                $allowedRoles[] = trim($single);
            }
        }

        if (!in_array(Auth::user()->level, $allowedRoles)) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        return $next($request);
    }
}