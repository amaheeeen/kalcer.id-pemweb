<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate();
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        /** @var \App\Models\User $user */ // Tambahkan ini untuk membantu VS Code
        $user = Auth::user();

        $user->update([ // Sekarang 'update' dikenali
            'password' => Hash::make($validated['password']),
        ]);

        // 
    }
}; ?>

<x-settings.layout heading="Update Password" subheading="Pastikan akunmu aman dengan password yang kuat.">
    
    <form wire:submit="updatePassword" class="space-y-6 max-w-xl">
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Password Saat Ini</label>
            <input wire:model="current_password" type="password" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                   autocomplete="current-password">
            @error('current_password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
            <input wire:model="password" type="password" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                   autocomplete="new-password">
            @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
            <input wire:model="password_confirmation" type="password" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                   autocomplete="new-password">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition transform">
                Update Password
            </button>

            <span x-data="{ show: false }"
                  x-show="show"
                  x-transition
                  x-init="@this.on('password-updated', () => { show = true; setTimeout(() => show = false, 2000); })"
                  class="text-sm text-green-600 font-bold"
                  style="display: none;">
                🔒 Berhasil Diupdate!
            </span>
        </div>
    </form>

</x-settings.layout>