<?php

namespace App\Providers;

use App\Models\Cita;
use App\Models\HomeSetting;
use App\Observers\CitaObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        Cita::observe(CitaObserver::class);
        View::composer('*', function ($view) {
            $homeSetting = HomeSetting::first();
            $view->with('homeSetting', $homeSetting);
        });
    }
}
