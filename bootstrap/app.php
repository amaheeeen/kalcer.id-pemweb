<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'business' => \App\Http\Middleware\IsBusiness::class, // Middleware Pemilik Usaha
            'is_admin' => \App\Http\Middleware\IsAdmin::class,    // Middleware Administrator (Baru)
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();