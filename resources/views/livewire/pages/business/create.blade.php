<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\HangoutPlace;
use Illuminate\Support\Facades\Auth;

new 
#[Layout('components.layouts.app')]
#[Title('Daftarkan Bisnis')]
class extends Component {
    
    // Form Inputs
    public $name, $address, $category = 'Coffee', $description, $operational_hours = '10:00 - 22:00';
    
    // Auto-fill latitude/longitude (Default Jaksel)
    public $latitude = -6.261493;
    public $longitude = 106.810600;

    public function mount()
    {
        // Jika user sudah punya bisnis, jangan boleh bikin lagi (Aturan MVP)
        if (Auth::user()->business) {
            return $this->redirect(route('business.index'), navigate: true);
        }
    }

    public function createBusiness()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string|min:10',
            'operational_hours' => 'required|string',
        ]);

        // 1. Buat Tempat Baru
        $place = HangoutPlace::create([
            'user_id' => Auth::id(), // Langsung assign ke user ini
            'name' => $this->name,
            'address' => $this->address,
            'category' => $this->category,
            'description' => $this->description,
            'operational_hours' => $this->operational_hours,
            'latitude' => $this->latitude, // Nanti bisa dikembangin pake Map Picker
            'longitude' => $this->longitude,
            'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24', // Default Image
            'viral_score' => 0,
            'profile_views' => 0,
            'crowd_level' => 'sepi',
            'is_verified' => true, // Auto verify untuk demo
        ]);

        // 2. Update Role User
        Auth::user()->update(['role' => 'business_owner']);

        // 3. Redirect ke Dashboard
        session()->flash('message', 'Bisnis berhasil didaftarkan! Selamat datang di Kalcer Business. 🚀');
        return $this->redirect(route('business.index'), navigate: true);
    }
}; ?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    
    <div class="max-w-3xl w-full">
        <div class="text-center mb-10">
            <span class="inline-block p-3 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mb-4">
                <i class="fa-solid fa-rocket text-2xl"></i>
            </span>
            <h1 class="text-3xl md:text-5xl font-black font-syne text-zinc-900 dark:text-white mb-4">
                Bisnismu Belum Terdaftar?
            </h1>
            <p class="text-zinc-500 dark:text-zinc-400 text-lg max-w-2xl mx-auto">
                Jangan mau kalah sama kompetitor. Daftarkan cafe atau tempat nongkrongmu sekarang dan jangkau ribuan anak Jaksel!
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-3xl p-8 md:p-10 shadow-2xl border border-zinc-200 dark:border-zinc-700">
            <form wire:submit="createBusiness" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Nama Bisnis</label>
                        <input wire:model="name" type="text" placeholder="Contoh: Kopi Senja Jaksel" class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 focus:ring-indigo-500 dark:text-white py-3 px-4 text-lg font-bold">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Kategori</label>
                        <select wire:model="category" class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 focus:ring-indigo-500 dark:text-white py-3 px-4">
                            <option value="Coffee">☕ Coffee Shop</option>
                            <option value="Restaurant">🍽️ Restaurant</option>
                            <option value="Bar/Lounge">🍸 Bar / Lounge</option>
                            <option value="Park">🌳 Taman / Outdoor</option>
                            <option value="Creative Space">🎨 Creative Space</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Jam Buka</label>
                        <input wire:model="operational_hours" type="text" placeholder="10:00 - 22:00" class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 focus:ring-indigo-500 dark:text-white py-3 px-4">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Alamat Lengkap</label>
                        <input wire:model="address" type="text" placeholder="Jl. Senopati No..." class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 focus:ring-indigo-500 dark:text-white py-3 px-4">
                        @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Deskripsi Singkat</label>
                        <textarea wire:model="description" rows="3" placeholder="Ceritakan vibe tempatmu..." class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 focus:ring-indigo-500 dark:text-white py-3 px-4"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black text-lg rounded-2xl shadow-lg shadow-indigo-500/30 hover:scale-[1.02] transition transform">
                        🚀 Luncurkan Bisnis Saya
                    </button>
                    <p class="text-center text-xs text-zinc-400 mt-4">
                        Dengan mendaftar, Anda setuju bahwa data bisnis Anda akan ditampilkan secara publik.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>