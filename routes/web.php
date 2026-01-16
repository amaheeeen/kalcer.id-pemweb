<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Http\Controllers\BusinessController;

// --- 1. PUBLIC ROUTES (Bisa diakses siapa saja) ---
Volt::route('/', 'pages.home')->name('home');
Volt::route('/maps', 'pages.maps')->name('maps');
Volt::route('/trending', 'pages.trending')->name('trending');
Volt::route('/about', 'pages.about')->name('about');
Volt::route('/place/{place}', 'pages.show')->name('place.show');
Volt::route('/explore', 'pages.explore')->name('explore');
Volt::route('/wishlist', 'pages.wishlist')->name('wishlist');

// --- 2. AUTHENTICATION (Volt) ---
Volt::route('/login', 'auth.login')->name('login');
Volt::route('/register', 'auth.register')->name('register');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

// --- 3. SMART DASHBOARD REDIRECT ---
// Route ini hanya bertugas mengarahkan user ke halaman yang tepat berdasarkan role
Route::get('/dashboard', function () {
    $user = Auth::user();

    // 1. Jika Admin -> Ke Admin Dashboard
    if ($user->role === 'admin') {
        return redirect()->route('business.dashboard');
    }
    
    // 2. Jika Business Owner -> Ke Halaman Kelola Bisnis
    if ($user->role === 'business_owner') {
        return redirect()->route('business.index');
    }

    // 3. Jika User Biasa -> Ke Profile Settings
    return redirect()->route('profile.edit');
})->middleware(['auth'])->name('dashboard');


// --- 4. BUSINESS OWNER ROUTES ---
// Menggunakan middleware 'auth' saja agar user biasa bisa masuk untuk klaim bisnis
Route::middleware(['auth'])->group(function () {
    Route::get('/business', [BusinessController::class, 'index'])->name('business.index');
    Route::post('/business/claim', [BusinessController::class, 'claim'])->name('business.claim');
    Route::post('/business/promo/{id}', [BusinessController::class, 'updatePromo'])->name('business.promo');
    Volt::route('/business/create', 'pages.business.create')->name('business.create');
});


// --- 5. ADMINISTRATOR ROUTES ---
// Dilindungi middleware 'is_admin' yang sudah kita buat
Route::middleware(['auth', 'is_admin'])->group(function () {
    // Route ini bernama 'business.dashboard' agar sesuai dengan link di Sidebar Admin
    Route::get('/admin/dashboard', [BusinessController::class, 'adminDashboard'])->name('business.dashboard');
});


// --- 6. SETTINGS (User Profile) ---
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Volt::route('/admin/dashboard', 'pages.admin.dashboard')->name('admin.dashboard');
});


// --- 0. UTILITIES (Language Switcher) ---
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');