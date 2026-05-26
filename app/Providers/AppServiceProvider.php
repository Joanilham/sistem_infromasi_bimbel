<?php

namespace App\Providers;

use App\Models\Kantor;
use App\Models\Master;
use App\Models\Periode;
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

        // Daftarkan listener autentikasi (login/logout audit log)
        \Illuminate\Support\Facades\Event::subscribe(\App\Listeners\LogAuthenticationEvents::class);

        // Daftarkan Observers untuk Invalidation Otomatis
        \App\Models\PesertaDidik::observe(\App\Observers\PesertaDidikObserver::class);
        \App\Models\PaketBimbingan::observe(\App\Observers\PaketBimbinganObserver::class);
        \App\Models\KelompokBelajar::observe(\App\Observers\KelompokBelajarObserver::class);
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
