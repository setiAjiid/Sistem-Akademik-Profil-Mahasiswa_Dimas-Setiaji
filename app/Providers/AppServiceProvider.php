<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        // Gate global: hanya dosen dan asdos yang boleh memberi nilai pada proposal.
        // Dipakai di Blade lewat @can('input-nilai') dan di controller lewat Gate::authorize().
        Gate::define('input-nilai', fn (User $user) => $user->adalahPengelola());

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
