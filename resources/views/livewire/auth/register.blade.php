<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;

new 
#[Layout('components.layouts.auth')] 
class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'user'; 

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,business_owner'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        Auth::login($user);

        $this->redirect(route('dashboard'), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="text-center mb-2">
        <h2 class="text-2xl font-black font-syne text-white tracking-tight">Join the Circle.</h2>
        <p class="text-sm text-zinc-400 mt-1">Daftar sekarang untuk akses penuh.</p>
    </div>

    <form wire:submit="register" class="flex flex-col gap-4">
        
        {{-- Name --}}
        <div class="space-y-1">
            <label for="name" class="text-xs font-bold uppercase tracking-wider text-zinc-400 ml-1">Nama Lengkap</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-zinc-500 group-focus-within:text-pink-500 transition-colors"></i>
                </div>
                <input wire:model="name" id="name" type="text" required autofocus
                    class="w-full bg-zinc-900/50 border border-zinc-700 text-white rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all placeholder-zinc-600"
                    placeholder="John Doe">
            </div>
            @error('name') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
        </div>

        {{-- Email --}}
        <div class="space-y-1">
            <label for="email" class="text-xs font-bold uppercase tracking-wider text-zinc-400 ml-1">Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-zinc-500 group-focus-within:text-pink-500 transition-colors"></i>
                </div>
                <input wire:model="email" id="email" type="email" required
                    class="w-full bg-zinc-900/50 border border-zinc-700 text-white rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all placeholder-zinc-600"
                    placeholder="nama@email.com">
            </div>
            @error('email') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
        </div>

        {{-- Role --}}
        <div class="space-y-1">
            <label class="text-xs font-bold uppercase tracking-wider text-zinc-400 ml-1">Tujuan Mendaftar</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer relative">
                    <input type="radio" wire:model="role" value="user" class="peer sr-only">
                    <div class="p-3 rounded-xl border border-zinc-700 bg-zinc-900/30 text-center hover:bg-zinc-800 peer-checked:border-pink-500 peer-checked:bg-pink-500/10 transition-all">
                        <i class="fa-solid fa-user-astronaut text-xl mb-1 block peer-checked:text-pink-500 text-zinc-500"></i>
                        <span class="text-xs font-bold peer-checked:text-white text-zinc-400">Visitor</span>
                    </div>
                </label>
                <label class="cursor-pointer relative">
                    <input type="radio" wire:model="role" value="business_owner" class="peer sr-only">
                    <div class="p-3 rounded-xl border border-zinc-700 bg-zinc-900/30 text-center hover:bg-zinc-800 peer-checked:border-purple-600 peer-checked:bg-purple-600/10 transition-all">
                        <i class="fa-solid fa-shop text-xl mb-1 block peer-checked:text-purple-600 text-zinc-500"></i>
                        <span class="text-xs font-bold peer-checked:text-white text-zinc-400">Pemilik Bisnis</span>
                    </div>
                </label>
            </div>
            @error('role') <span class="text-red-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
        </div>

        {{-- Password --}}
        <div class="space-y-1">
            <label for="password" class="text-xs font-bold uppercase tracking-wider text-zinc-400 ml-1">Password</label>
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

        {{-- Confirm Password --}}
        <div class="space-y-1">
            <label for="password_confirmation" class="text-xs font-bold uppercase tracking-wider text-zinc-400 ml-1">Konfirmasi Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-check-double text-zinc-500 group-focus-within:text-pink-500 transition-colors"></i>
                </div>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" required
                    class="w-full bg-zinc-900/50 border border-zinc-700 text-white rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition-all placeholder-zinc-600"
                    placeholder="••••••••">
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full mt-4 bg-purple-600 text-white font-black py-3.5 rounded-xl text-sm uppercase tracking-widest hover:bg-pink-500 hover:shadow-[0_0_20px_rgba(168,85,247,0.5)] transition-all transform hover:-translate-y-1 duration-300">
            Daftar Sekarang <i class="fa-solid fa-rocket ml-2"></i>
        </button>
    </form>

    <div class="text-center text-sm text-zinc-500 mt-2">
        Sudah punya akun? 
        <a href="{{ route('login') }}" wire:navigate class="font-bold text-white hover:text-pink-500 hover:underline transition-colors decoration-wavy">
            Login di sini
        </a>
    </div>
</div>