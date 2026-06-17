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
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super Admin memiliki akses penuh ke semua fitur
        if (strtolower($user->level) === 'super admin') {
            return $next($request);
        }

        // Otomatis menentukan izin granular (create, update, delete) berdasarkan HTTP Method & Route Name
        $baseModule = str_replace('manage_', '', $permission);
        $requiredPermission = $permission; // Default: manage_xxx (Lihat)

        $method = $request->method();
        $routeName = $request->route() ? $request->route()->getName() : '';

        // Deteksi Create (Tambah)
        if ($method === 'POST' || (is_string($routeName) && str_ends_with($routeName, '.create'))) {
            $requiredPermission = 'create_' . $baseModule;
        } 
        // Deteksi Update (Edit)
        elseif (in_array($method, ['PUT', 'PATCH']) || (is_string($routeName) && str_ends_with($routeName, '.edit'))) {
            $requiredPermission = 'update_' . $baseModule;
        } 
        // Deteksi Delete (Hapus)
        elseif ($method === 'DELETE' || (is_string($routeName) && str_ends_with($routeName, '.destroy'))) {
            $requiredPermission = 'delete_' . $baseModule;
        }

        if (!$user->hasPermission($requiredPermission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki hak akses untuk aksi ini.'], 403);
            }
            return redirect()->route('dashboard')->withErrors(['error' => 'Akses ditolak. Anda tidak memiliki hak akses untuk aksi ini.']);
        }

        return $next($request);
    }
}
