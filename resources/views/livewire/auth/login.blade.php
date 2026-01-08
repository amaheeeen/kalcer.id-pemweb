<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout; // <--- Import Attribute Layout
use Livewire\Volt\Component;

new 
#[Layout('components.layouts.auth')] // <--- Definisikan Layout di sini
class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

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

        if (Auth::user()->role === 'business_owner') {
            $this->redirect(route('business.dashboard'), navigate: true);
        } else {
            $this->redirect(route('home'), navigate: true);
        }
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="text-center">
        <h2 class="text-xl font-bold text-gray-900">Selamat Datang Kembali</h2>
        <p class="text-sm text-gray-500">Masuk untuk melanjutkan</p>
    </div>

    <form wire:submit="login" class="flex flex-col gap-5">
        
        <flux:input wire:model="email" label="Email" type="email" required autofocus placeholder="nama@email.com" />

        <div class="relative">
            <flux:input wire:model="password" label="Password" type="password" required viewable placeholder="••••••••" />
        </div>

        <flux:checkbox wire:model="remember" label="Ingat saya" />

        <flux:button variant="primary" type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition">
            Masuk Sekarang
        </flux:button>
    </form>

    <div class="text-center text-sm text-gray-600">
        Belum punya akun? <a href="{{ route('register') }}" wire:navigate class="font-bold text-purple-600 hover:underline">Daftar di sini</a>
    </div>
</div>