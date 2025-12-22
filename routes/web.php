<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// --- JALUR DARURAT BUAT SEEDING DATABASE ---
Route::get('/fix-database-sekarang', function () {
    // 1. Paksa migrasi ulang (hapus semua tabel & buat baru)
    Artisan::call('migrate:fresh', ['--force' => true]);
    
    // 2. Isi data dummy (Seeding)
    Artisan::call('db:seed', ['--force' => true]);
    
    // 3. Link storage gambar
    Artisan::call('storage:link');
    
    return "<h1>✅ SUKSES! Database Railway sudah di-reset & di-isi.</h1>";
});

// --- BAGIAN 1: KALCER.ID (PUBLIC) ---

// Halaman Utama
Volt::route('/', 'pages.home')->name('home');

// Halaman Detail
Volt::route('/place/{place}', 'pages.show')->name('place.show');

// ... route home ...

// Halaman Maps (Split Screen)
Volt::route('/maps', 'pages.maps')->name('maps');

// ... route maps ...

// Halaman Trending
Volt::route('/trending', 'pages.trending')->name('trending');

// ... route trending ...

// Halaman About & Contact
Volt::route('/about', 'pages.about')->name('about');

// --- BAGIAN 2: DASHBOARD & SETTINGS (LOGIN REQUIRED) ---

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Redirect /settings ke /settings/profile
    Route::redirect('settings', 'settings/profile');

    // 1. Profile Settings
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');

    // 2. Password Settings (NAMA ROUTE DISESUAIKAN)
    // View bawaan mencari 'user-password.edit', bukan 'password.edit'
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');

    // 3. Appearance Settings (NAMA ROUTE DISESUAIKAN)
    // View bawaan mencari 'appearance.edit', bukan 'appearance'
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // 4. Two Factor Auth
    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    
});