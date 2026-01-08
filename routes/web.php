<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Jangan lupa ini!
use Livewire\Volt\Volt;
use App\Http\Controllers\BusinessController;

// --- BAGIAN 1: PUBLIC PAGES ---

Volt::route('/', 'pages.home')->name('home');
Volt::route('/maps', 'pages.maps')->name('maps');
Volt::route('/trending', 'pages.trending')->name('trending');
Volt::route('/about', 'pages.about')->name('about');
Volt::route('/place/{place}', 'pages.show')->name('place.show');

// --- BAGIAN 2: AUTHENTICATION (Volt) ---
// Kita pakai Volt untuk Login/Register agar bisa Custom Redirect (User vs Business)
Volt::route('/login', 'auth.login')->name('login');
Volt::route('/register', 'auth.register')->name('register');

// Route Logout Manual (Penting untuk Navbar)
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');


// --- BAGIAN 3: DASHBOARD & FITUR KHUSUS ---

// Redirect Dashboard Cerdas (Cek Role)
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'business_owner') {
        return redirect()->route('business.dashboard');
    }
    return redirect()->route('profile.edit');
})->middleware(['auth'])->name('dashboard');

// Group Route khusus Business Owner
Route::middleware(['auth', 'business'])->prefix('business')->name('business.')->group(function () {
    Route::get('/dashboard', [BusinessController::class, 'index'])->name('dashboard');
    Route::post('/claim', [BusinessController::class, 'claim'])->name('claim');
    Route::post('/promo/{id}', [BusinessController::class, 'updatePromo'])->name('promo');
});

// Group Settings untuk User Biasa
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});