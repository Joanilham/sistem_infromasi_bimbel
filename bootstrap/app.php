<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Percayai semua proxy (ngrok, Cloudflare, dll)
        // Penting agar session, CSRF, dan HTTPS detection berjalan benar via tunnel
        $middleware->trustProxies(at: '*');

        // Global middleware — berjalan di SEMUA request
        // $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        // $middleware->append(\App\Http\Middleware\CameraPermissionHeaders::class);

        // Alias middleware
        $middleware->alias([
            'role'        => \App\Http\Middleware\RoleMiddleware::class,
            'ensure_role' => \App\Http\Middleware\EnsureCorrectRole::class,
            'konteks'     => \App\Http\Middleware\CekKonteks::class,
            'check_payment'=> \App\Http\Middleware\CheckPaymentStatus::class,
            'check_permission' => \App\Http\Middleware\CheckPermission::class,
            'check_installment_status' => \App\Http\Middleware\CheckInstallmentStatus::class,
        ]);

        // Pengecualian CSRF untuk endpoint scanner QR & logout (menghindari error 419 via Ngrok/AJAX)
        // Login & siswa/ujian/* juga dikecualikan sementara untuk keperluan load testing Artillery
        $middleware->validateCsrfTokens(except: [
            'absensi/scan-masuk',
            'absensi/scan-pulang',
            'logout',
            'login',
            'siswa/ujian/*/mulai',
            'siswa/ujian/*/jawab',
            'siswa/ujian/*/submit',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // ── 404 Not Found ──────────────────────────────────────
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (!$request->expectsJson()) {
                return response()->view('errors.404', [], 404);
            }
        });

        // ── CSRF Token Mismatch (419) ──────────────────────────
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if (!$request->expectsJson()) {
                return response()->view('errors.419', [], 419);
            }
        });

        // ── Unauthenticated (401) → redirect ke login ──────────
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (!$request->expectsJson()) {
                return redirect()->route('login');
            }
        });

        // ── HTTP Exceptions (403, 429, 500, 503, dll) ──────────
        $exceptions->render(function (HttpException $e, Request $request) {
            if (!$request->expectsJson()) {
                $code = $e->getStatusCode();
                $viewPath = "errors.{$code}";

                if ($code >= 500) {
                    \Illuminate\Support\Facades\Log::error("HTTP ERROR $code: " . $e->getMessage());
                }

                // Fallback ke 500 jika tidak ada view untuk kode tersebut
                if (!view()->exists($viewPath)) {
                    $viewPath = 'errors.500';
                    $code = 500;
                }

                return response()->view($viewPath, [], $code);
            }
        });

        // ── Exception lain yang tidak tertangkap ───────────────
        // (hanya aktif saat APP_DEBUG=false / production)
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Jangan intercept ValidationException, biarkan Laravel meredirect back
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return null;
            }

            if (!$request->expectsJson() && !config('app.debug')) {
                \Illuminate\Support\Facades\Log::error("FATAL ERROR: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                return response()->view('errors.500', [], 500);
            }
        });

    })->create();
