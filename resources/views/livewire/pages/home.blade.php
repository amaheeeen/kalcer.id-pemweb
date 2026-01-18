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
    
    <section class="relative h-[100dvh] flex items-center justify-center overflow-hidden">
        
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none">
            {{-- Lingkaran 1 --}}
            <div class="absolute w-[300px] h-[300px] md:w-[400px] md:h-[400px] rounded-full border-2 border-indigo-500/30 dark:border-indigo-400/20 animate-shockwave"></div>
            {{-- Lingkaran 2 --}}
            <div class="absolute w-[300px] h-[300px] md:w-[400px] md:h-[400px] rounded-full border-2 border-purple-500/20 dark:border-purple-400/10 animate-shockwave animate-delay-1000"></div>
            {{-- Lingkaran 3 --}}
            <div class="absolute w-[300px] h-[300px] md:w-[400px] md:h-[400px] rounded-full border-2 border-blue-500/10 dark:border-blue-400/5 animate-shockwave animate-delay-2000"></div>
        </div>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] md:w-[800px] md:h-[800px] bg-indigo-50 dark:bg-indigo-900/10 blur-[80px] md:blur-[120px] rounded-full animate-blob pointer-events-none"></div>
        
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto space-y-6 md:space-y-8">
            <div class="flex justify-center">
                <span class="inline-flex items-center gap-2 py-1.5 px-3 md:px-4 rounded-full bg-zinc-100 dark:bg-white/5 backdrop-blur-lg border border-zinc-200 dark:border-white/10 text-zinc-500 dark:text-zinc-400 text-[10px] md:text-xs font-black tracking-[0.2em] uppercase shadow-sm">
                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    Live Jakarta Selatan Guide
                </span>
            </div>

            {{-- RESPONSIVE TEXT FIX --}}
            <h1 class="text-4xl min-[400px]:text-5xl sm:text-6xl md:text-8xl lg:text-9xl font-black text-zinc-900 dark:text-white tracking-tighter leading-[0.9] md:leading-[0.85] font-syne">
                TEMUKAN <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-500">VIBES.</span>
            </h1>
            
            <p class="text-base min-[400px]:text-lg md:text-xl text-zinc-500 dark:text-zinc-400 max-w-xs min-[400px]:max-w-md md:max-w-2xl mx-auto leading-relaxed font-medium">
                Kurasi tempat nongkrong paling valid di Jakarta Selatan. <br class="hidden md:block"> Dari hidden gem Senopati sampai rooftop SCBD.
            </p>
            
            <div class="pt-6 md:pt-8 flex flex-col sm:flex-row gap-4 sm:gap-5 justify-center w-full px-4 sm:px-0">
                <a href="{{ route('maps') }}" wire:navigate class="w-full sm:w-auto group px-8 py-4 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-2xl font-black text-base md:text-lg transition-all hover:scale-105 shadow-xl hover:shadow-2xl">
                    JELAJAHI PETA
                </a>
                <a href="{{ route('trending') }}" wire:navigate class="w-full sm:w-auto group px-8 py-4 bg-white dark:bg-zinc-900 border-2 border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white rounded-2xl font-black text-base md:text-lg transition-all hover:bg-zinc-50 dark:hover:bg-zinc-800">
                    TRENDING 🔥
                </a>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce text-zinc-300 dark:text-zinc-700 pointer-events-none">
            <i class="fa-solid fa-chevron-down text-lg md:text-xl"></i>
        </div>
    </section>

    <section class="py-20 md:py-32 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 md:mb-16 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-black font-syne text-zinc-900 dark:text-white tracking-tight">
                    Rekomendasi <span class="text-indigo-600">Minggu Ini</span>
                </h2>
                <p class="text-sm md:text-base text-zinc-500 dark:text-zinc-400 mt-2">Spot paling valid menurut algoritma Kalcer.</p>
            </div>
            <a href="{{ route('trending') }}" wire:navigate class="flex items-center gap-2 text-sm font-bold text-indigo-600 hover:underline">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
            @foreach($recommendations as $place)
                <div class="group relative bg-white dark:bg-zinc-900 rounded-[2rem] border border-zinc-100 dark:border-zinc-800 overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col h-full active:scale-[0.98] md:active:scale-100">
                    
                    <div class="relative h-60 md:h-72 overflow-hidden">
                        <img src="{{ $place['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur text-[10px] font-black uppercase tracking-widest rounded-full text-zinc-900 shadow-xl">
                                {{ $place['badge'] }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 flex flex-col flex-1">
                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-[0.2em] mb-2 md:mb-3">
                            {{ $place['category'] }}
                        </span>
                        
                        <h3 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white mb-2 md:mb-3 group-hover:text-indigo-600 transition">
                            {{ $place['name'] }}
                        </h3>
                        
                        <p class="text-zinc-500 dark:text-zinc-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 line-clamp-2">
                            {{ $place['description'] }}
                        </p>
                        
                        <div class="pt-4 md:pt-6 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between mt-auto">
                            <div class="flex items-center text-zinc-400 text-[10px] font-bold uppercase tracking-wider">
                                <i class="fa-solid fa-location-dot mr-2"></i> <span class="truncate max-w-[120px]">{{ $place['location'] }}</span>
                            </div>
                            <div class="text-orange-500 font-black text-sm">⭐ {{ $place['rating'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="relative py-24 md:py-40 px-4 bg-zinc-900 dark:bg-white text-center rounded-[2rem] md:rounded-[3rem] mx-4 mb-8 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 pointer-events-none"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto space-y-6 md:space-y-10">
            <h2 class="text-4xl md:text-7xl font-black tracking-tighter text-white dark:text-zinc-900 font-syne leading-tight">
                GABUNG <span class="italic font-normal">CIRCLE</span> <br> 
                PALING VALID.
            </h2>
            
            <p class="text-zinc-400 dark:text-zinc-500 text-base md:text-xl max-w-xl mx-auto leading-relaxed">
                Dapatkan akses ke Hidden Gems, review jujur, dan komunitas paling valid se-Jakarta Selatan.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-4 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white rounded-2xl font-black text-lg hover:scale-105 transition shadow-2xl">
                    JOIN SEKARANG 🚀
                </a>
            </div>
        </div>
    </section>
</div>