<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new 
#[Layout('components.layouts.auth')] 
class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

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
        $this->redirect(route('dashboard'), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="text-center mb-2">
        <h2 class="text-2xl font-black font-syne text-white tracking-tight">Welcome Back!</h2>
        <p class="text-sm text-zinc-400 mt-1">Masuk untuk memantau spot viral.</p>
    </div>

    <form wire:submit="login" class="flex flex-col gap-5">
        
        {{-- Email --}}
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-bold uppercase tracking-wider text-zinc-400 ml-1">Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-zinc-500 group-focus-within:text-pink-500 transition-colors"></i>
                </div>
                <input wire:model="email" id="email" type="email" required autofocus 
                    class="w-full bg-zinc-900/50 border border-zinc-700 text-white rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all placeholder-zinc-600"
                    placeholder="nama@email.com">
            </div>
            @error('email') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
        </div>

        {{-- Password --}}
        <div class="space-y-1.5">
            <div class="flex justify-between items-center ml-1">
                <label for="password" class="text-xs font-bold uppercase tracking-wider text-zinc-400">Password</label>
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-zinc-500 group-focus-within:text-pink-500 transition-colors"></i>
                </div>
                <input wire:model="password" id="password" type="password" required 
                    class="w-full bg-zinc-900/50 border border-zinc-700 text-white rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all placeholder-zinc-600"
                    placeholder="••••••••">
            </div>
            @error('password') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between mt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded bg-zinc-800 border-zinc-600 text-pink-500 focus:ring-pink-500 focus:ring-offset-zinc-900">
                <span class="text-sm text-zinc-400">Ingat saya</span>
            </label>
            
            <a href="{{ route('password.request') }}" wire:navigate class="text-xs font-bold text-purple-500 hover:text-pink-500 transition-colors">
                Lupa Password?
            </a>
        </div>

        {{-- Button --}}
        <button type="submit" class="w-full mt-4 bg-white text-black font-black py-3.5 rounded-xl text-sm uppercase tracking-widest hover:bg-pink-500 hover:text-white hover:shadow-[0_0_20px_rgba(236,72,153,0.5)] transition-all transform hover:-translate-y-1 duration-300">
            Masuk Sekarang <i class="fa-solid fa-arrow-right ml-2"></i>
        </button>
    </form>

    <div class="text-center text-sm text-zinc-500 mt-4">
        Belum punya akun? 
        <a href="{{ route('register') }}" wire:navigate class="font-bold text-white hover:text-pink-500 hover:underline transition-colors decoration-wavy">
            Daftar dulu, gratis!
        </a>
    </div>
</div>