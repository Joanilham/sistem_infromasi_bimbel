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
        // Validasi session dengan database untuk menghindari error foreign key (misal data sudah dihapus)
        if ($request->session()->has('periode_id')) {
            $periodeExists = \App\Models\MasterData\Periode::where('id', $request->session()->get('periode_id'))->exists();
            if (!$periodeExists) {
                $request->session()->forget('periode_id');
            }
        }

        if ($request->session()->has('kantor_id') && $request->session()->get('kantor_id') !== 'all') {
            $kantorExists = \App\Models\MasterData\Kantor::where('id', $request->session()->get('kantor_id'))->exists();
            if (!$kantorExists) {
                $request->session()->forget('kantor_id');
            }
        }

        if (!$request->session()->has('kantor_id') || !$request->session()->has('periode_id')) {
            $user = $request->user();
            if ($user && in_array(strtolower($user->level), ['super admin', 'admin', 'staff'])) {
                $isSuperAdmin = strtolower($user->level) === 'super admin';
                $kantorId = $isSuperAdmin ? 'all' : ($user->kantor_id ?: (\App\Models\MasterData\Kantor::first()->id ?? null));
                $periodeId = $user->periode_id ?: (\App\Models\MasterData\Periode::where('is_active', true)->first()->id ?? (\App\Models\MasterData\Periode::first()->id ?? null));

                // Pastikan periodeId yang diambil dari user benar-benar valid di database
                if ($periodeId && !\App\Models\MasterData\Periode::where('id', $periodeId)->exists()) {
                    $periodeId = \App\Models\MasterData\Periode::where('is_active', true)->first()->id ?? (\App\Models\MasterData\Periode::first()->id ?? null);
                }

                if ($kantorId && $kantorId !== 'all' && !\App\Models\MasterData\Kantor::where('id', $kantorId)->exists()) {
                    $kantorId = \App\Models\MasterData\Kantor::first()->id ?? null;
                }

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


