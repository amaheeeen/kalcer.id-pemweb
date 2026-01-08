<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'user'; // Default

    public function toJSON() { return; }

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,business_owner'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Illuminate\Auth\Events\Registered($user = User::create($validated)));

        Auth::login($user);

        if ($user->role === 'business_owner') {
            $this->redirect(route('business.dashboard'), navigate: true);
        } else {
            $this->redirect(route('home'), navigate: true);
        }
    }
}; ?>

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="Buat Akun Baru" description="Bergabung dengan Kalcer.id" />

        <form wire:submit="register" class="flex flex-col gap-6">
            
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" wire:model="role" value="user" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:bg-gray-50 transition text-center">
                        <div class="font-bold">Pengunjung 😎</div>
                    </div>
                </label>
                
                <label class="cursor-pointer">
                    <input type="radio" wire:model="role" value="business_owner" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:bg-gray-50 transition text-center">
                        <div class="font-bold">Pemilik Usaha 💼</div>
                    </div>
                </label>
            </div>

            <flux:input wire:model="name" label="Nama Lengkap" type="text" required />
            <flux:input wire:model="email" label="Email" type="email" required />
            <flux:input wire:model="password" label="Password" type="password" required viewable />
            <flux:input wire:model="password_confirmation" label="Konfirmasi Password" type="password" required viewable />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white">
                    Daftar
                </flux:button>
            </div>
        </form>

        <div class="text-center text-sm text-zinc-600">
            Sudah punya akun? <flux:link :href="route('login')" wire:navigate class="font-bold text-indigo-600">Masuk</flux:link>
        </div>
    </div>
</x-layouts.auth>