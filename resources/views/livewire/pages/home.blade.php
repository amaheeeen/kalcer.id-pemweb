<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    public function with(): array
    {
        return [
            'recommendations' => [
                [
                    'id' => 1,
                    'name' => 'Tanatap Coffee Ampera',
                    'category' => 'Artistic & WFC',
                    'image' => 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=800&auto=format&fit=crop',
                    'rating' => 4.8,
                    'reviews' => 1240,
                    'location' => 'Ampera, Jakarta Selatan',
                    'badge' => '🔥 Viral',
                    'description' => 'Spot WFC favorit arsitek Jaksel. Konsep "Ring Garden" yang menggabungkan area duduk outdoor dengan atap hijau melingkar.'
                ],
                [
                    'id' => 2,
                    'name' => 'Urban Forest Cipete',
                    'category' => 'Nature & Chill',
                    'image' => 'https://images.unsplash.com/photo-1620916297397-a4a5402a3c6c?q=80&w=800&auto=format&fit=crop',
                    'rating' => 4.9,
                    'reviews' => 3100,
                    'location' => 'Cipete, Jakarta Selatan',
                    'badge' => '🌿 Healing',
                    'description' => 'Hutan kota estetik tempat healing tipis-tipis. Banyak tenant hits (Solo Pizza, El Profesor) dengan suasana asri pepohonan rindang.'
                ],
                [
                    'id' => 3,
                    'name' => 'Oddity Coffee Senopati',
                    'category' => 'Aesthetic & Brunch',
                    'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=800&auto=format&fit=crop',
                    'rating' => 4.7,
                    'reviews' => 850,
                    'location' => 'Senopati, Jakarta Selatan',
                    'badge' => '✨ OOTD Spot',
                    'description' => 'Cafe berkonsep Brutalist yang unik. Interior dominasi semen ekspos dan kopi specialty, wajib mampir buat konten Instagram.'
                ],
            ]
        ];
    }
}; ?>

<div class="min-h-screen bg-white dark:bg-zinc-950 transition-colors duration-700 overflow-x-hidden">
    
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            {{-- Lingkaran 1 --}}
            <div class="absolute w-[400px] h-[400px] rounded-full border-2 border-indigo-500/30 dark:border-indigo-400/20 animate-shockwave"></div>
            {{-- Lingkaran 2 --}}
            <div class="absolute w-[400px] h-[400px] rounded-full border-2 border-purple-500/20 dark:border-purple-400/10 animate-shockwave animate-delay-1000"></div>
            {{-- Lingkaran 3 --}}
            <div class="absolute w-[400px] h-[400px] rounded-full border-2 border-blue-500/10 dark:border-blue-400/5 animate-shockwave animate-delay-2000"></div>
        </div>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-50 dark:bg-indigo-900/10 blur-[120px] rounded-full animate-blob"></div>
        
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto space-y-8">
            <div class="flex justify-center">
                <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-zinc-100 dark:bg-white/5 backdrop-blur-lg border border-zinc-200 dark:border-white/10 text-zinc-500 dark:text-zinc-400 text-[10px] font-black tracking-[0.2em] uppercase">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    Live Jakarta Selatan Guide
                </span>
            </div>

            <h1 class="text-6xl md:text-8xl lg:text-9xl font-black text-zinc-900 dark:text-white tracking-tighter leading-[0.85] font-syne">
                TEMUKAN <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-500">VIBES.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto leading-relaxed font-medium">
                Kurasi tempat nongkrong paling valid di Jakarta Selatan. <br class="hidden md:block"> Dari hidden gem Senopati sampai rooftop SCBD.
            </p>
            
            <div class="pt-8 flex flex-col sm:flex-row gap-5 justify-center">
                <a href="{{ route('maps') }}" wire:navigate class="group px-10 py-5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-2xl font-syne font-black text-lg transition-all hover:scale-105 shadow-2xl">
                    JELAJAHI PETA
                </a>
                <a href="{{ route('trending') }}" wire:navigate class="group px-10 py-5 bg-white dark:bg-zinc-900 border-2 border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white rounded-2xl font-syne font-black text-lg transition-all hover:bg-zinc-50 dark:hover:bg-zinc-800">
                    TRENDING 🔥
                </a>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-zinc-300 dark:text-zinc-700">
            <i class="fa-solid fa-chevron-down text-xl"></i>
        </div>
    </section>

    <section class="py-32 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-16">
            <div>
                <h2 class="text-4xl font-black font-syne text-zinc-900 dark:text-white tracking-tight">
                    Rekomendasi <span class="text-indigo-600">Minggu Ini</span>
                </h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2">Spot paling valid menurut algoritma Kalcer.</p>
            </div>
            <a href="{{ route('trending') }}" wire:navigate class="hidden md:flex items-center gap-2 text-sm font-bold text-indigo-600 hover:underline">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @foreach($recommendations as $place)
                <div class="group relative bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-zinc-100 dark:border-zinc-800 overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
                    
                    <div class="relative h-72 overflow-hidden">
                        <img src="{{ $place['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-1.5 bg-white/90 backdrop-blur text-[10px] font-black uppercase tracking-widest rounded-full text-zinc-900 shadow-xl">
                                {{ $place['badge'] }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8 flex flex-col flex-1">
                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-[0.2em] mb-3">
                            {{ $place['category'] }}
                        </span>
                        
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3 group-hover:text-indigo-600 transition">
                            {{ $place['name'] }}
                        </h3>
                        
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed mb-6 line-clamp-2">
                            {{ $place['description'] }}
                        </p>
                        
                        <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between mt-auto">
                            <div class="flex items-center text-zinc-400 text-[10px] font-bold uppercase tracking-wider">
                                <i class="fa-solid fa-location-dot mr-2"></i> {{ $place['location'] }}
                            </div>
                            <div class="text-orange-500 font-black text-sm">⭐ {{ $place['rating'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="relative py-40 px-4 bg-zinc-900 dark:bg-white text-center rounded-[3rem] mx-4 mb-8 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 pointer-events-none"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto space-y-10">
            <h2 class="text-5xl md:text-7xl font-black tracking-tighter text-white dark:text-zinc-900 font-syne">
                GABUNG <span class="italic font-normal">SIRCLE</span> <br> 
                PALING VALID.
            </h2>
            
            <p class="text-zinc-400 dark:text-zinc-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                Dapatkan akses ke Hidden Gems, review jujur, dan komunitas paling valid se-Jakarta Selatan.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center pt-6">
                <a href="{{ route('register') }}" class="px-12 py-5 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white rounded-2xl font-black text-xl hover:scale-105 transition shadow-2xl">
                    JOIN SEKARANG 🚀
                </a>
            </div>
        </div>
    </section>
</div>