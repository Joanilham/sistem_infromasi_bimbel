<?php

namespace App\Providers;

use App\Models\Kantor;
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
        // Share daftar kantor dan periode ke seluruh view (digunakan di header selector)
        // Dibungkus try-catch agar tidak error saat migration belum selesai
        try {
            View::share('kantors', Kantor::all());
            View::share('periodes', Periode::all());
        } catch (\Exception $e) {
            // Tabel mungkin belum ada (saat migration pertama kali)
            View::share('kantors', collect());
            View::share('periodes', collect());
        }
    }
}
