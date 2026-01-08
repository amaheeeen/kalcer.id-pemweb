<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsBusiness
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login DAN role-nya 'business'
        if (Auth::check() && Auth::user()->role === 'business_owner') {
            return $next($request);
        }

        // Kalau bukan, tendang ke home
        return redirect('/');
    }
}