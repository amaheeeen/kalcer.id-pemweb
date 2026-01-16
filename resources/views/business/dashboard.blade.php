<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\HangoutPlace;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

new 
#[Layout('components.layouts.app')]
#[Title('Business Dashboard')]
class extends Component {
    public $place;
    
    // Form Inputs
    public $name, $description, $operational_hours, $promo_text, $promo_expires_at;

    public function mount()
    {
        $user = Auth::user();

        // LOGIC AUTO-CLAIM / ASSIGN (Untuk Demo)
        if (!$user->business) {
            // Cari tempat nganggur buat demo
            $freePlace = HangoutPlace::whereNull('user_id')->first();
            if ($freePlace) {
                $freePlace->update(['user_id' => $user->id]);
                $this->place = $freePlace;
            } else {
                // Jika tidak ada tempat nganggur, redirect ke home
                return $this->redirect(route('home'));
            }
        } else {
            $this->place = $user->business;
        }

        // Isi form
        $this->name = $this->place->name;
        $this->description = $this->place->description;
        $this->operational_hours = $this->place->operational_hours;
        $this->promo_text = $this->place->promo_text;
        
        if ($this->place->promo_expires_at) {
            $this->promo_expires_at = Carbon::parse($this->place->promo_expires_at)->format('Y-m-d');
        }
    }

    public function updateBusiness()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'operational_hours' => 'required|string',
            'promo_text' => 'nullable|string|max:50', // Max 50 biar pas di UI
            'promo_expires_at' => 'nullable|date',
        ]);

        $this->place->update([
            'name' => $this->name,
            'description' => $this->description,
            'operational_hours' => $this->operational_hours,
            'promo_text' => $this->promo_text,
            'promo_expires_at' => $this->promo_expires_at,
        ]);

        session()->flash('message', 'Bisnis berhasil diupdate! 🚀');
    }
}; ?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 pb-20 transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-8 px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="relative overflow-hidden rounded-3xl p-8 md:p-12 text-white shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-violet-600 via-indigo-600 to-purple-600 animate-gradient-x"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/20 text-xs font-bold mb-2 tracking-wider">
                        🚀 BUSINESS MODE
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 font-syne">
                        Hi, {{ explode(' ', auth()->user()->name)[0] }}!
                    </h1>
                    <p class="text-indigo-100 text-lg">Siap bikin <span class="font-bold underline">{{ $place->name }}</span> viral hari ini?</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl flex items-center gap-3 hover:bg-white/20 transition cursor-default">
                    <div class="w-10 h-10 rounded-full bg-green-400 flex items-center justify-center text-black font-bold shadow-lg shadow-green-400/20">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-xs text-indigo-200 uppercase font-bold">Status Listing</p>
                        <p class="font-bold text-white">Verified Owner</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-6 rounded-3xl relative group overflow-hidden hover:border-indigo-500/50 transition duration-500 shadow-sm">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition"></div>
                <div class="flex justify-between items-start mb-8">
                    <div class="p-3 bg-zinc-100 dark:bg-zinc-700/50 rounded-xl">
                        <i class="fa-solid fa-chart-simple text-indigo-500"></i>
                    </div>
                    <span class="text-green-500 text-xs font-bold bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded">+12% vs last week</span>
                </div>
                <h3 class="text-zinc-400 text-sm font-bold uppercase tracking-wider mb-1">Total Profile Views</h3>
                <p class="text-4xl font-black text-zinc-900 dark:text-white">{{ number_format($place->profile_views) }}</p>
            </div>

            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-6 rounded-3xl relative group overflow-hidden hover:border-amber-500/50 transition duration-500 shadow-sm">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition"></div>
                <div class="flex justify-between items-start mb-8">
                    <div class="p-3 bg-zinc-100 dark:bg-zinc-700/50 rounded-xl">
                        <i class="fa-solid fa-fire text-amber-500"></i>
                    </div>
                    <span class="text-amber-500 text-xs font-bold bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded">Trending</span>
                </div>
                <h3 class="text-zinc-400 text-sm font-bold uppercase tracking-wider mb-1">Viral Score</h3>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-black text-zinc-900 dark:text-white">{{ $place->viral_score }}</p>
                    <span class="text-zinc-500 text-sm">/ 100</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-pink-600 to-rose-600 p-6 rounded-3xl text-white flex flex-col justify-between shadow-xl shadow-pink-500/20">
                <div>
                    <h3 class="font-bold text-xl mb-1">Boost Traffic! 🚀</h3>
                    <p class="text-pink-100 text-sm opacity-90">Pasang promo biar anak Jaksel makin sering mampir ke tempatmu.</p>
                </div>
                <button onclick="document.getElementById('promo-input').focus()" class="mt-4 bg-white text-pink-600 font-bold py-3 rounded-xl hover:bg-pink-50 transition shadow-lg">
                    Pasang Promo Sekarang
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-8 relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50"></div>
                
                <h3 class="text-2xl font-bold text-white mb-2">📢 Live Promo Board</h3>
                <p class="text-zinc-400 text-sm mb-6">Promo yang kamu tulis di sini akan muncul dengan badge <span class="text-indigo-400 font-bold">Special Offer</span> di halaman detail.</p>

                <div class="p-4 bg-black/50 rounded-xl border border-zinc-800 border-dashed mb-6">
                    <p class="text-xs text-zinc-500 mb-2 uppercase tracking-wide">Preview di Mata User:</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-zinc-800 rounded-lg flex items-center justify-center text-zinc-600">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <div>
                            <div class="h-2 w-20 bg-zinc-800 rounded mb-2"></div>
                            <span class="text-xs font-bold bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded border border-indigo-500/30">
                                {{ $promo_text ?: 'Belum ada promo aktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <form wire:submit="updateBusiness" class="space-y-4">
                    <div>
                        <label class="text-zinc-300 text-sm font-bold ml-1">Teks Promo (Max 50 Karakter)</label>
                        <div class="relative mt-2">
                            <input id="promo-input" type="text" wire:model.live="promo_text" 
                                    class="w-full bg-black border border-zinc-700 text-white rounded-xl p-4 pl-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder-zinc-700" 
                                    placeholder="Contoh: Diskon 20% pake KTM!">
                            <span class="absolute left-4 top-4 text-xl">🏷️</span>
                        </div>
                        @error('promo_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                         <label class="text-zinc-300 text-sm font-bold ml-1">Berlaku Sampai</label>
                         <input type="date" wire:model="promo_expires_at" class="w-full mt-2 bg-black border border-zinc-700 text-white rounded-xl p-4 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div class="flex items-center justify-between pt-2">
                        @if($promo_text)
                            <p class="text-green-400 text-xs flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                Live Preview
                            </p>
                        @else
                            <p class="text-zinc-500 text-xs">Tidak ada promo aktif</p>
                        @endif
                        
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                            <i class="fa-solid fa-bolt"></i> Update Promo
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-3xl p-8 shadow-sm h-fit">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">📝 Edit Informasi</h3>
                    <a href="{{ route('place.show', $place->id) }}" target="_blank" class="text-indigo-500 text-sm font-bold hover:underline">
                        Lihat Halaman <i class="fa-solid fa-arrow-up-right-from-square text-xs ml-1"></i>
                    </a>
                </div>

                @if (session()->has('message'))
                    <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> {{ session('message') }}
                    </div>
                @endif

                <form wire:submit="updateBusiness" class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Nama Bisnis</label>
                        <input wire:model="name" type="text" class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 focus:ring-indigo-500 dark:text-white py-3 px-4">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Deskripsi Singkat</label>
                        <textarea wire:model="description" rows="4" class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 focus:ring-indigo-500 dark:text-white py-3 px-4"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Jam Operasional</label>
                        <input wire:model="operational_hours" type="text" class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 focus:ring-indigo-500 dark:text-white py-3 px-4">
                    </div>

                    <button type="submit" class="w-full py-4 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold rounded-xl hover:opacity-90 transition shadow-lg mt-2">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>