<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// --- BAGIAN 1: KALCER.ID (PUBLIC) ---

// Halaman Utama
Volt::route('/', 'pages.home')->name('home');

// Halaman Detail
Volt::route('/place/{place}', 'pages.show')->name('place.show');

// Halaman Maps
Volt::route('/maps', 'pages.maps')->name('maps');

// Halaman Trending
Volt::route('/trending', 'pages.trending')->name('trending');

// Halaman About & Contact
Volt::route('/about', 'pages.about')->name('about');

// Halaman Search (Jika ada)
Volt::route('/search', 'pages.search')->name('search');

// Halaman Categories (Jika ada)
Volt::route('/categories', 'pages.categories')->name('categories');

// Redirect /business ke dashboard
Route::redirect('/business', '/business/dashboard');

// Halaman Auth (Login & Register via Volt)
Volt::route('/login', 'auth.login')->name('login');
Volt::route('/register', 'auth.register')->name('register');


// --- BAGIAN 2: DASHBOARD & SETTINGS (LOGIN REQUIRED) ---

// Route Logout Manual
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Group Route khusus Business Owner
Route::middleware(['auth', 'business'])->prefix('business')->name('business.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\BusinessController::class, 'index'])->name('dashboard');
    Route::post('/claim', [App\Http\Controllers\BusinessController::class, 'claim'])->name('claim');
    Route::post('/promo/{id}', [App\Http\Controllers\BusinessController::class, 'updatePromo'])->name('promo');
});

// Dashboard User Biasa (Settings dll)
Route::middleware(['auth'])->group(function () {
    Route::redirect('dashboard', 'settings/profile')->name('dashboard');
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    
    // Two Factor Auth Logic
    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            \Laravel\Fortify\Features::canManageTwoFactorAuthentication()
            && \Laravel\Fortify\Features::optionEnabled(\Laravel\Fortify\Features::twoFactorAuthentication(), 'confirmPassword')
            ? ['password.confirm']
            : []
        )->name('two-factor.show');
});