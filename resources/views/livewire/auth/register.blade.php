<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout; // <--- Import Attribute Layout
use Livewire\Volt\Component;

new 
#[Layout('components.layouts.auth')] // <--- Definisikan Layout di sini
class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'user';

    public function toJSON() { return; }

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,business_owner'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        Auth::login($user);

        if ($user->role === 'business_owner') {
            $this->redirect(route('business.dashboard'), navigate: true);
        } else {
            $this->redirect(route('home'), navigate: true);
        }
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="text-center">
        <h2 class="text-xl font-bold text-gray-900">Buat Akun Baru</h2>
        <p class="text-sm text-gray-500">Gabung komunitas Kalcer.id</p>
    </div>

    <form wire:submit="register" class="flex flex-col gap-5">
        
        <div class="grid grid-cols-2 gap-4">
            <label class="cursor-pointer group">
                <input type="radio" wire:model="role" value="user" class="peer sr-only">
                <div class="p-3 rounded-lg border-2 border-gray-200 peer-checked:border-purple-600 peer-checked:bg-purple-50 group-hover:border-purple-300 transition text-center">
                    <div class="text-2xl mb-1">😎</div>
                    <div class="font-bold text-gray-700 text-sm">Pengunjung</div>
                </div>
            </label>
            
            <label class="cursor-pointer group">
                <input type="radio" wire:model="role" value="business_owner" class="peer sr-only">
                <div class="p-3 rounded-lg border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 group-hover:border-amber-300 transition text-center">
                    <div class="text-2xl mb-1">💼</div>
                    <div class="font-bold text-gray-700 text-sm">Pemilik Usaha</div>
                </div>
            </label>
        </div>

        <flux:input wire:model="name" label="Nama Lengkap" type="text" required />
        <flux:input wire:model="email" label="Email" type="email" required />
        <flux:input wire:model="password" label="Password" type="password" required viewable />
        <flux:input wire:model="password_confirmation" label="Konfirmasi Password" type="password" required viewable />

        <flux:button variant="primary" type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition">
            Daftar Sekarang
        </flux:button>
    </form>

    <div class="text-center text-sm text-gray-600">
        Sudah punya akun? <a href="{{ route('login') }}" wire:navigate class="font-bold text-purple-600 hover:underline">Masuk</a>
    </div>
</div>