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
        // Daftarkan Observers untuk Invalidation Otomatis
        \App\Models\PesertaDidik::observe(\App\Observers\PesertaDidikObserver::class);
        \App\Models\PaketBimbingan::observe(\App\Observers\PaketBimbinganObserver::class);
        \App\Models\KelompokBelajar::observe(\App\Observers\KelompokBelajarObserver::class);
        Kantor::observe(\App\Observers\KantorObserver::class);
        Periode::observe(\App\Observers\PeriodeObserver::class);

        // Share daftar kantor dan periode ke seluruh view dengan Caching Forever
        // Cache akan terhapus otomatis via Observer jika data ditable berubah
        try {
            $kantors = \Illuminate\Support\Facades\Cache::rememberForever('global_kantors', fn() => Kantor::all());
            $periodes = \Illuminate\Support\Facades\Cache::rememberForever('global_periodes', fn() => Periode::all());
            $masterData = \Illuminate\Support\Facades\Cache::rememberForever('global_master', fn() => Master::first());

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
