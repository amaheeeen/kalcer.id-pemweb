<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Http\Controllers\BusinessController;

// --- 1. PUBLIC ROUTES ---
Volt::route('/', 'pages.home')->name('home');
Volt::route('/maps', 'pages.maps')->name('maps');
Volt::route('/trending', 'pages.trending')->name('trending');
Volt::route('/about', 'pages.about')->name('about');
Volt::route('/place/{place}', 'pages.show')->name('place.show');

// --- 2. AUTHENTICATION (Volt) ---
Volt::route('/login', 'auth.login')->name('login');
Volt::route('/register', 'auth.register')->name('register');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

// --- 3. DASHBOARD & BUSINESS ---
// Redirect pintar berdasarkan Role
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'business_owner') {
        return redirect()->route('business.dashboard');
    }
    return redirect()->route('profile.edit');
})->middleware(['auth'])->name('dashboard');

// Dashboard Bisnis
Route::middleware(['auth', 'business'])->prefix('business')->name('business.')->group(function () {
    Route::get('/dashboard', [BusinessController::class, 'index'])->name('dashboard');
    Route::post('/claim', [BusinessController::class, 'claim'])->name('claim');
    Route::post('/promo/{id}', [BusinessController::class, 'updatePromo'])->name('promo');
});

// Settings User Biasa
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});

// --- 0. UTILITIES (Language Switcher) ---
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');