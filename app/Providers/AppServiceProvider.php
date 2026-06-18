<?php

namespace App\Providers;

use App\Models\MasterData\Kantor;
use App\Models\MasterData\Master;
use App\Models\MasterData\Periode;
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

        // Share daftar kantor dan periode ke seluruh view dengan Caching Forever
        // Cache akan terhapus otomatis via Observer jika data ditable berubah
        try {
            $kantors    = \Illuminate\Support\Facades\Cache::remember('global_kantors',  86400, fn() => Kantor::all());
            $periodes   = \Illuminate\Support\Facades\Cache::remember('global_periodes',  86400, fn() => Periode::all());
            $masterData = \Illuminate\Support\Facades\Cache::remember('global_master',    86400, fn() => Master::first());

            View::share('kantors', $kantors);
            View::share('periodes', $periodes);
            View::share('masterData', $masterData);

            if ($masterData && $masterData->mail_host) {
                config([
                    'mail.mailers.smtp.host' => $masterData->mail_host,
                    'mail.mailers.smtp.port' => $masterData->mail_port,
                    'mail.mailers.smtp.encryption' => $masterData->mail_encryption,
                    'mail.mailers.smtp.username' => $masterData->mail_username,
                    'mail.mailers.smtp.password' => $masterData->mail_password,
                    'mail.from.address' => $masterData->mail_from_address,
                    'mail.from.name' => $masterData->mail_from_name,
                ]);
            }
        } catch (\Exception $e) {
            View::share('galleries', collect());
            View::share('testimonials', collect());
            View::share('faqs', collect());
            View::share('kantors', collect());
            View::share('periodes', collect());
            View::share('masterData', null);
        }
    }
}

