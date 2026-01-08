<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    // Fungsi dummy untuk mencegah error browser extension
    public function toJSON() { return; }

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        session()->regenerate();

        // LOGIKA REDIRECT: Pemilik Usaha ke Dashboard Bisnis, User Biasa ke Home
        if (Auth::user()->role === 'business_owner') {
            $this->redirect(route('business.dashboard'), navigate: true);
        } else {
            $this->redirect(route('home'), navigate: true);
        }
    }
}; ?>

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="Selamat Datang" description="Masuk untuk melanjutkan" />

        <form wire:submit="login" class="flex flex-col gap-6">
            
            <flux:input wire:model="email" label="Email" type="email" required autofocus placeholder="email@contoh.com" />

            <div class="relative">
                <flux:input wire:model="password" label="Password" type="password" required viewable placeholder="Password" />
            </div>

            <flux:checkbox wire:model="remember" label="Ingat saya" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white">
                    Masuk
                </flux:button>
            </div>
        </form>

        <div class="text-center text-sm text-zinc-600">
            Belum punya akun? <flux:link :href="route('register')" wire:navigate class="font-bold text-indigo-600">Daftar sekarang</flux:link>
        </div>
    </div>
</x-layouts.auth>