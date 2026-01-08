<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // HANYA paksa HTTPS jika di Production (Railway).
        // Di Localhost, biarkan HTTP agar tidak BLANK.
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}