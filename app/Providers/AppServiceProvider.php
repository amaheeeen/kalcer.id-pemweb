<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; 

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
        // Paksa HTTPS kalau di Production (Railway)
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        if (config('app.env') === 'production') {
        \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }  
}    