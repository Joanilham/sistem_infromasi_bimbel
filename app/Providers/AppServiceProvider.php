<?php

namespace App\Providers;

use App\Models\MasterData\Kantor;
use App\Models\MasterData\Master;
use App\Models\MasterData\Periode;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan Custom Default Pagination View
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');

        // Daftarkan Gate untuk Log Viewer (Hanya Super Admin yang bisa akses)
        \Illuminate\Support\Facades\Gate::define('viewLogViewer', function ($user) {
            return strtolower($user->level) === 'super admin';
        });

        // Daftarkan Custom Driver Google Drive
        \Illuminate\Support\Facades\Storage::extend('google', function ($app, $config) {
            $client = new \Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            
            $token = $client->fetchAccessTokenWithRefreshToken($config['refreshToken']);
            if (isset($token['error'])) {
                $tokenPrefix = substr($config['refreshToken'], 0, 15) . '...';
                throw new \Exception("Google Token Error: " . json_encode($token) . " | Token yang dipakai server saat ini: " . $tokenPrefix);
            }
            
            $service = new \Google_Service_Drive($client);
            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folderId'] ?? '');
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new \League\Flysystem\Filesystem($adapter),
                $adapter,
                $config
            );
        });

        // Daftarkan listener autentikasi (login/logout audit log)
        \Illuminate\Support\Facades\Event::subscribe(\App\Listeners\LogAuthenticationEvents::class);

        // Daftarkan Observers untuk Invalidation Otomatis
        \App\Models\Akademik\PesertaDidik::observe(\App\Observers\PesertaDidikObserver::class);
        \App\Models\Akademik\PaketBimbingan::observe(\App\Observers\PaketBimbinganObserver::class);
        \App\Models\Akademik\KelompokBelajar::observe(\App\Observers\KelompokBelajarObserver::class);
        Kantor::observe(\App\Observers\KantorObserver::class);
        Periode::observe(\App\Observers\PeriodeObserver::class);

        // Profiling Octane Lifecycle
        \Illuminate\Support\Facades\Event::listen(\Laravel\Octane\Events\RequestReceived::class, function ($event) {
            $event->request->attributes->set('__octane_t0', microtime(true));
        });
        \Illuminate\Support\Facades\Event::listen(\Laravel\Octane\Events\RequestHandled::class, function ($event) {
            $t0 = $event->request->attributes->get('__octane_t0');
            $ms = $t0 ? sprintf('%.2f', (microtime(true) - $t0) * 1000) : '0';
            error_log("[PERF] RequestHandled: {$ms} ms");
        });
        \Illuminate\Support\Facades\Event::listen(\Laravel\Octane\Events\RequestTerminated::class, function ($event) {
            $t0 = $event->request->attributes->get('__octane_t0');
            $ms = $t0 ? sprintf('%.2f', (microtime(true) - $t0) * 1000) : '0';
            error_log("[PERF] RequestTerminated: {$ms} ms");
        });

        // Nonaktifkan Debugbar untuk memangkas overhead profiling dan latency per-request
        if (class_exists(\Barryvdh\Debugbar\Facades\Debugbar::class)) {
            \Barryvdh\Debugbar\Facades\Debugbar::disable();
        }

        // Share daftar kantor dan periode ke seluruh view dengan Caching Forever
        // Cache akan terhapus otomatis via Observer jika data ditable berubah
        // Memoize per-request via $request->attributes agar tidak query berulang untuk setiap sub-view (16x partials)
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $viewName = $view->getName();
            if (str_starts_with($viewName, 'errors.') || str_starts_with($viewName, 'errors::')) {
                return;
            }

            $req = request();

            // Validasi integritas platform — dieksekusi maksimal 1x per HTTP request
            if ($req && !$req->attributes->get('__integrity_checked')) {
                \App\Support\Security\PlatformIntegrity::assertIntegrity();
                $req->attributes->set('__integrity_checked', true);
            }

            try {
                $viewData = $view->getData();
                
                // Ambil shared data dari request attribute (1x hit cache per request alih-alih 16x per sub-view)
                $shared = $req ? $req->attributes->get('__global_view_data') : null;
                if ($shared === null) {
                    $kantors = \Illuminate\Support\Facades\Cache::remember('global_kantors', 86400, fn() => \App\Models\MasterData\Kantor::all());
                    $periodes = \Illuminate\Support\Facades\Cache::remember('global_periodes', 86400, fn() => \App\Models\MasterData\Periode::all());
                    $masterData = \Illuminate\Support\Facades\Cache::remember('global_master', 86400, fn() => \App\Models\MasterData\Master::first());
                    
                    if ($masterData) {
                        if (!empty($masterData->nama_lembaga)) {
                            config(['app.name' => $masterData->nama_lembaga]);
                        }

                        if ($masterData->mail_host) {
                            config([
                                'mail.mailers.smtp.host' => $masterData->mail_host,
                                'mail.mailers.smtp.port' => $masterData->mail_port,
                                'mail.mailers.smtp.encryption' => $masterData->mail_encryption,
                                'mail.mailers.smtp.username' => $masterData->mail_username,
                                'mail.mailers.smtp.password' => $masterData->mail_password,
                                'mail.from.address' => $masterData->mail_from_address,
                                'mail.from.name' => $masterData->mail_from_name ?: ($masterData->nama_lembaga ?: config('app.name')),
                            ]);
                        }
                    }

                    $shared = [
                        'kantors' => $kantors,
                        'periodes' => $periodes,
                        'masterData' => $masterData,
                    ];

                    if ($req) {
                        $req->attributes->set('__global_view_data', $shared);
                    }
                }

                if (!array_key_exists('kantors', $viewData)) {
                    $view->with('kantors', $shared['kantors']);
                }

                if (!array_key_exists('periodes', $viewData)) {
                    $view->with('periodes', $shared['periodes']);
                }
                
                if (!array_key_exists('masterData', $viewData)) {
                    $view->with('masterData', $shared['masterData']);
                }
            } catch (\Exception $e) {
                $viewData = $view->getData();
                if (!array_key_exists('kantors', $viewData)) $view->with('kantors', collect());
                if (!array_key_exists('periodes', $viewData)) $view->with('periodes', collect());
                if (!array_key_exists('masterData', $viewData)) $view->with('masterData', null);
            }
        });

        \Illuminate\Support\Facades\Blade::directive('highlight', function ($expression) {
            return "<?php 
                \$__args = [$expression];
                \$__text = \$__args[0] ?? '';
                \$__search = \$__args[1] ?? request()->query('search');
                if (\$__search) {
                    \$__escapedSearch = preg_quote(\$__search, '/');
                    echo preg_replace('/(' . \$__escapedSearch . ')/i', '<mark class=\"bg-yellow-300 dark:bg-yellow-700/50 text-inherit rounded-sm px-0.5\">$1</mark>', e(\$__text));
                } else {
                    echo e(\$__text);
                }
            ?>";
        });
    }
}

