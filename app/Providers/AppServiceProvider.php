<?php

namespace App\Providers;

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
        // Challenge 1: mode tema dibaca dari ?mode=dark dan dibagikan ke semua view.
        // Pakai composer (bukan View::share) karena nilainya bergantung pada request yang sedang dirender.
        View::composer('*', function ($view) {
            $modeGelap = request()->query('mode') === 'dark';

            $view->with([
                'modeGelap' => $modeGelap,
                // Ditambahkan ke route() di navbar dan form supaya mode gelap tidak hilang saat pindah halaman.
                'kueriMode' => $modeGelap ? ['mode' => 'dark'] : [],
                // URL halaman ini dengan mode kebalikannya, untuk tombol ganti tema.
                'urlGantiMode' => $modeGelap
                    ? request()->fullUrlWithoutQuery(['mode'])
                    : request()->fullUrlWithQuery(['mode' => 'dark']),
            ]);
        });
    }
}
