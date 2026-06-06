<?php

namespace App\Http\Middleware;
use App\Models\MasterData\Periode;
use App\Models\MasterData\Kantor;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekKonteks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('kantor_id') || !$request->session()->has('periode_id')) {
            $user = $request->user();
            if ($user && in_array(strtolower($user->level), ['super admin', 'admin'])) {
                $isSuperAdmin = strtolower($user->level) === 'super admin';
                $kantorId = $isSuperAdmin ? 'all' : ($user->kantor_id ?: (\App\Models\MasterData\Kantor::first()->id ?? null));
                $periodeId = $user->periode_id ?: (\App\Models\MasterData\Periode::where('is_active', true)->first()->id ?? (\App\Models\MasterData\Periode::first()->id ?? null));

                if ($kantorId && $periodeId) {
                    $request->session()->put('kantor_id', $kantorId);
                    $request->session()->put('periode_id', $periodeId);
                    return $next($request);
                }

                return redirect()->route('konteks.select')->with('error', 'Silakan pilih Kantor dan Periode terlebih dahulu untuk mengakses fitur ini.');
            }
            return redirect()->route('dashboard')->with('error', 'Fitur belum tersedia untuk akses spesifik ini.');
        }

        return $next($request);
    }
}


