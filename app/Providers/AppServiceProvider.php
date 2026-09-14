<?php

namespace App\Providers;

use App\Data\DataStatis;
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
        // NRP pemilik dipakai di navbar layout, jadi dibagikan ke semua view.
        View::share('nrpPemilik', DataStatis::NRP_PEMILIK);
    }
}
