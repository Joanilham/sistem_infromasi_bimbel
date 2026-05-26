<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPaymentStatus
{
    /**
     * Handle an incoming request.
     *
     * Mengecek 2 hal:
     * 1. Apakah akun sudah diverifikasi Admin (status 'aktif')
     * 2. Apakah siswa memiliki tagihan yang menunggak (jatuh tempo/habis masa aktif)
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // =========================================================================
            // 1. CEK STATUS VERIFIKASI PENDAFTARAN (Logika Baru)
            // =========================================================================
            if ($user->status !== 'aktif') {
                
                // Rute pengecualian yang boleh diakses meskipun belum diverifikasi
                if ($request->routeIs('siswa.dashboard') || 
                    $request->routeIs('siswa.profile.*') || 
                    $request->routeIs('siswa.pembayaran.*') || 
                    $request->routeIs('logout')) {
                    
                    // Langsung izinkan lewat (Bypass cek tagihan di bawah, karena akun belum diverifikasi)
                    return $next($request);
                }

                // Jika mencoba akses Ujian, Jadwal, dll, lemparkan kembali ke Dashboard
                return redirect()->route('siswa.dashboard')
                    ->with('pending_message', 'Akun Anda sedang menunggu verifikasi dari Admin Pusat. Fitur kelas belum bisa diakses.');
            }


            // =========================================================================
            // 2. CEK STATUS TAGIHAN / PEMBAYARAN (Logika Asli Milikmu)
            // =========================================================================
            // Check if user is a student
            if ($user->level === 'siswa' || $user->pesertaDidik) {
                $pesertaDidik = $user->pesertaDidik;
                
                if ($pesertaDidik) {
                    $status = $pesertaDidik->getStatusPembayaran();
                    
                    if ($status['is_locked']) {
                        // List of allowed routes when locked
                        $allowedRoutes = [
                            'siswa.pembayaran.index',
                            'siswa.pembayaran.konfirmasi',
                            'siswa.pembayaran.nota',
                            'logout',
                        ];
                        
                        $currentRoute = $request->route() ? $request->route()->getName() : null;
                        
                        if ($currentRoute && !in_array($currentRoute, $allowedRoutes)) {
                            if ($status['is_overdue']) {
                                $errorMessage = 'Akses ditangguhkan sementara! Batas waktu (jatuh tempo) pembayaran Anda telah terlewati. Silakan selesaikan pembayaran tagihan Anda secara penuh atau hubungi admin.';
                            } else {
                                if ($status['sisa_hari'] < 0) {
                                    $errorMessage = 'Akses ditangguhkan! Masa durasi paket bimbingan Anda telah habis dan tagihan belum dilunasi. Silakan lakukan pembayaran penuh atau hubungi admin.';
                                } else {
                                    $errorMessage = "Akses ditangguhkan! Masa aktif paket bimbingan Anda tersisa kurang dari 1 bulan ({$status['sisa_hari']} hari) lagi dan pembayaran Anda belum lunas. Silakan lakukan pembayaran penuh untuk dapat terus mengakses menu bimbingan.";
                                }
                            }
                            
                            // If AJAX request or expects JSON, return a clean JSON 403 Forbidden
                            if ($request->expectsJson()) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => $errorMessage
                                ], 403);
                            }
                            
                            return redirect()->route('siswa.pembayaran.index')->with('error', $errorMessage);
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}