<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')]
class extends Component {
    // Profile Info
    public string $name = '';
    public string $email = '';
    
    // Password Update
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfile(): void
    {
        /** @var \App\Models\User $user */ // <--- SOLUSI 1: Beritahu editor ini Model User
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->fill($validated); // Sekarang 'fill' dikenali
        
        if ($user->isDirty('email')) { // Sekarang 'isDirty' dikenali
            $user->email_verified_at = null;
        }
        
        $user->save(); // Sekarang 'save' dikenali

        $this->dispatch('profile-updated');
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        /** @var \App\Models\User $user */ // <--- SOLUSI 2: Definisikan variabel dulu
        $user = Auth::user();
        
        $user->update([ // Sekarang 'update' dikenali
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('password-updated');
    }
}; ?>

{{-- ... (Kode HTML di bawah biarkan sama seperti sebelumnya) ... --}}
<div class="max-w-4xl mx-auto space-y-8">
    ```
    <form wire:submit="updateProfile" class="space-y-6">
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
            <input wire:model="name" type="text" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
            <input wire:model="email" type="email" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>  