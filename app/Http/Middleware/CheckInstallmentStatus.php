<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallmentStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Ensure user is siswa and has pesertaDidik
        if ($user && $user->level === 'siswa' && $user->pesertaDidik) {
            $pembayaran = $user->pesertaDidik->pembayaran;

            if ($pembayaran) {
                // Check if they are behind on payments and next due date has passed
                // And not given dispensasi
                $isOverdue = $pembayaran->kekurangan > 0 &&
                             $pembayaran->jatuh_tempo_berikutnya && 
                             $pembayaran->jatuh_tempo_berikutnya->startOfDay() < now()->startOfDay() &&
                             !$pembayaran->dispensasi;

                if ($isOverdue) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Akses ditangguhkan karena terdapat tagihan angsuran yang telah melewati jatuh tempo.'
                        ], 403);
                    }

                    return redirect()->route('siswa.locked');
                }
            }
        }

        return $next($request);
    }
}
