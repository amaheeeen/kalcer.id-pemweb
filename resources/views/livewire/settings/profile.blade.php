<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // Wajib untuk upload foto
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')]
class extends Component {
    use WithFileUploads; // Trait upload

    // Profile Data
    public $photo; // Temporary upload
    public $existingPhoto;
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $bio = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username ?? '';
        $this->email = $user->email;
        $this->bio = $user->bio ?? '';
        $this->existingPhoto = $user->avatar;
    }

    public function updateProfile(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:50'],
            'username' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id), 'alpha_dash'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:150'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        // Handle Photo Upload
        if ($this->photo) {
            $path = $this->photo->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'];

        if ($user->isDirty('email')) $user->email_verified_at = null;
        
        $user->save();

        $this->dispatch('profile-updated');
        
        // Reset input file agar bisa upload lagi
        $this->photo = null; 
        $this->existingPhoto = $user->avatar;
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="max-w-4xl mx-auto">
    
    <div class="mb-8 text-center md:text-left">
        <h1 class="brand-font text-3xl font-bold mb-2">Edit Profile</h1>
        <p class="text-gray-500 dark:text-gray-400">Make your profile look cool like on social media.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-3xl p-6 text-center shadow-xl shadow-gray-200/50 dark:shadow-none sticky top-28">
                
                <div class="relative w-32 h-32 mx-auto mb-4 group">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-32 h-32 rounded-full object-cover border-4 border-indigo-500">
                    @elseif ($existingPhoto)
                        <img src="{{ Storage::url($existingPhoto) }}" class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-white/20 shadow-lg">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gradient-to-tr from-indigo-400 to-pink-400 flex items-center justify-center text-4xl font-bold text-white shadow-lg">
                            {{ substr($name, 0, 1) }}
                        </div>
                    @endif

                    <label for="photo-upload" class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition cursor-pointer">
                        <span class="text-white text-xs font-bold">Change Photo</span>
                    </label>
                    <input wire:model="photo" id="photo-upload" type="file" class="hidden" accept="image/*">
                </div>

                <h2 class="font-bold text-lg truncate">{{ $name }}</h2>
                <p class="text-sm text-gray-500 mb-6">{{ '@'.($username ?: 'username') }}</p>

                <div class="w-full h-px bg-gray-100 dark:bg-white/10 my-4"></div>

                <button wire:click="logout" class="w-full py-2.5 rounded-xl border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 font-bold text-sm hover:bg-red-50 dark:hover:bg-red-900/20 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                
                <form wire:submit="updateProfile" class="space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Username</label>
                            <input wire:model="username" type="text" placeholder="cool_user" class="w-full bg-gray-50 dark:bg-black/20 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 font-medium focus:ring-2 focus:ring-indigo-500 transition">
                            @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Full Name</label>
                            <input wire:model="name" type="text" class="w-full bg-gray-50 dark:bg-black/20 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 font-medium focus:ring-2 focus:ring-indigo-500 transition">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Bio</label>
                        <textarea wire:model="bio" rows="3" placeholder="Tell the world about you..." class="w-full bg-gray-50 dark:bg-black/20 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 font-medium focus:ring-2 focus:ring-indigo-500 transition resize-none"></textarea>
                        <div class="text-right text-xs text-gray-400 mt-1">{{ strlen($bio) }}/150</div>
                        @error('bio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400">✉️</span>
                            <input wire:model="email" type="email" class="w-full bg-gray-50 dark:bg-black/20 border-gray-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-3 font-medium focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                        <x-action-message on="profile-updated" class="text-green-500 font-bold text-sm">
                            ✨ Saved successfully!
                        </x-action-message>

                        <button type="submit" class="bg-black dark:bg-white text-white dark:text-black px-8 py-3 rounded-xl font-bold hover:scale-105 transition shadow-lg">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>

            <div class="mt-8 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm opacity-80 hover:opacity-100 transition">
                <div class="flex justify-between items-center cursor-pointer" onclick="document.getElementById('password-form').classList.toggle('hidden')">
                    <div>
                        <h3 class="font-bold text-lg">Change Password</h3>
                        <p class="text-sm text-gray-500">Update your security key here.</p>
                    </div>
                    <span class="text-gray-400">▼</span>
                </div>
                
                <div id="password-form" class="hidden mt-6 pt-6 border-t border-gray-100 dark:border-white/5">
                    <livewire:settings.password /> 
                    </div>
            </div>
        </div>
    </div>
</div>