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
    // HTTPS Force logic (yang sebelumnya sudah ada)
    if (config('app.env') === 'production') {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }

    // Language Logic
    if (session()->has('locale')) {
        app()->setLocale(session('locale'));
    }
    }
}