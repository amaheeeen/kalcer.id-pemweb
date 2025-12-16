<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }
}; ?>

<x-settings.layout heading="Profile Information" subheading="Update nama tampilan dan alamat email profil kamu.">
    
    <form wire:submit="updateProfileInformation" class="space-y-6 max-w-xl">
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
            <input wire:model="name" type="text" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                   required autofocus autocomplete="name">
            @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
            <input wire:model="email" type="email" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                   required autocomplete="username">
            @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition transform">
                Simpan Perubahan
            </button>

            <span x-data="{ show: false }"
                  x-show="show"
                  x-transition
                  x-init="@this.on('profile-updated', () => { show = true; setTimeout(() => show = false, 2000); })"
                  class="text-sm text-green-600 font-bold"
                  style="display: none;">
                ✅ Tersimpan!
            </span>
        </div>
    </form>

</x-settings.layout>