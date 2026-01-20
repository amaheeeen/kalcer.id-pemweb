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
    
    <section 
        class="relative h-[100dvh] w-full overflow-hidden flex flex-col justify-center items-center bg-zinc-50 dark:bg-zinc-950"
        x-data="{
            ripples: [],
            addRipple(e) {
                if (Math.random() > 0.4) return; 
                const x = e.clientX;
                const y = e.clientY;
                const id = Date.now();
                this.ripples.push({ id, x, y });
                setTimeout(() => {
                    this.ripples = this.ripples.filter(r => r.id !== id);
                }, 1000);
            }
        }"
        @mousemove="addRipple"
        @touchmove="addRipple"
    >
        
        {{-- LAYER 1: BACKGROUND MARQUEE --}}
        <div class="absolute inset-0 flex flex-col justify-center opacity-[0.03] dark:opacity-[0.05] pointer-events-none select-none overflow-hidden -rotate-3 scale-110">
            @php
                $marqueeText = "KALCER.ID • PANTAU SPOT VIRAL • JAKSEL PRIDE • HIDDEN GEMS • SCBD • SENOPATI • BLOK M • ";
            @endphp
            
            @for($i=0; $i<5; $i++)
                <div class="flex whitespace-nowrap animate-marquee" style="animation-duration: {{ 25 + ($i*5) }}s; animation-direction: {{ $i % 2 == 0 ? 'normal' : 'reverse' }}">
                    <span class="text-[12vh] font-black font-syne text-zinc-900 dark:text-white uppercase leading-none">
                        {{ str_repeat($marqueeText, 3) }}
                    </span>
                </div>
            @endfor
        </div>

        {{-- LAYER 2: INTERACTIVE RIPPLE --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-10">
            <template x-for="ripple in ripples" :key="ripple.id">
                <div 
                    class="absolute rounded-full border border-indigo-500/30 dark:border-white/20 bg-indigo-500/5 dark:bg-white/5 backdrop-blur-[2px] animate-ripple"
                    :style="`left: ${ripple.x}px; top: ${ripple.y}px; width: 100px; height: 100px; margin-left: -50px; margin-top: -50px;`"
                ></div>
            </template>
        </div>

        {{-- LAYER 3: FOREGROUND HERO --}}
        <div class="relative z-20 text-center px-4 w-full max-w-7xl mx-auto mt-[-5vh]">
            
            {{-- Top Badge --}}
            <div class="mb-6 flex justify-center">
                <div class="relative group cursor-pointer">
                    <div class="absolute inset-0 bg-indigo-500 blur-lg opacity-50 group-hover:opacity-100 transition duration-500"></div>
                    <span class="relative px-6 py-2 bg-zinc-900 dark:bg-white text-white dark:text-black text-xs font-black tracking-[0.3em] uppercase border-2 border-transparent dark:border-zinc-200 skew-x-[-10deg] inline-block hover:skew-x-0 transition-transform duration-300 rounded-lg">
                        The Ultimate Guide
                    </span>
                </div>
            </div>

            {{-- Main Typography (CENTERED FIX) --}}
            {{-- Menggunakan flex-col items-center untuk memaksa tengah sempurna --}}
            <h1 class="flex flex-col items-center justify-center font-black font-syne leading-[0.85] tracking-tighter select-none w-full">
                
                {{-- Baris 1: Outline Text --}}
                <span class="block text-[15vw] md:text-[160px] text-center text-transparent [-webkit-text-stroke:2px_#18181b] dark:[-webkit-text-stroke:2px_#ffffff] hover:text-zinc-900 dark:hover:text-white transition-colors duration-500 cursor-default w-full">
                    TEMUKAN
                </span>
                
                {{-- Baris 2: Solid Gradient Text + Ikon --}}
                <div class="flex items-center justify-center gap-2 md:gap-6 flex-wrap mt-[-2vw] md:mt-[-40px] w-full">
                    <span class="text-[15vw] md:text-[160px] text-center text-transparent bg-clip-text bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 drop-shadow-2xl">
                        VIBES
                    </span>
                    <i class="fa-solid fa-star-of-life text-4xl md:text-7xl text-zinc-900 dark:text-white animate-spin-slow opacity-80"></i>
                </div>
            </h1>

            <p class="max-w-xl mx-auto text-lg md:text-xl font-bold text-zinc-500 dark:text-zinc-400 mt-8 mb-12 leading-relaxed">
                Platform kurasi tempat nongkrong paling di Jakarta Selatan. 
                <br class="hidden md:block"> Jangan sampai FOMO, cek spot viral sekarang.
            </p>

            {{-- ROUNDED BUTTONS FIX --}}
            <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                
                <a href="{{ route('explore') }}" wire:navigate class="relative px-10 py-5 bg-zinc-900 dark:bg-white text-white dark:text-black font-black text-lg uppercase tracking-wider hover:-translate-y-2 hover:shadow-[0px_10px_20px_rgba(79,70,229,0.4)] transition-all duration-300 border-2 border-transparent rounded-full">
                    Gas Explore 🚀
                </a>

                <a href="{{ route('maps') }}" wire:navigate class="relative px-10 py-5 bg-transparent text-zinc-900 dark:text-white font-black text-lg uppercase tracking-wider border-2 border-zinc-900 dark:border-white hover:bg-zinc-900 hover:text-white dark:hover:bg-white dark:hover:text-black transition-all duration-300 rounded-full">
                    Buka Peta 🗺️
                </a>

            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-zinc-400 dark:text-zinc-600 animate-bounce">
            <span class="text-[10px] font-bold uppercase tracking-widest rotate-90 origin-center mb-4">Scroll</span>
            <div class="w-[1px] h-12 bg-zinc-400 dark:bg-zinc-600"></div>
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

    <section class="relative py-24 md:py-40 px-4 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] bg-zinc-900 dark:bg-white text-center rounded-[2rem] md:rounded-[3rem] mx-4 mb-8 overflow-hidden group">
        
        {{-- Background Noise --}}
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 pointer-events-none"></div>
        
        {{-- COLOR FADE / GLOW EFFECT (BARU) --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[200px] h-[200px] md:w-[500px] md:h-[500px] bg-indigo-600/30 dark:bg-indigo-400/20 blur-[80px] md:blur-[120px] rounded-full pointer-events-none animate-pulse"></div>

        <div class="relative z-10 max-w-4xl mx-auto space-y-6 md:space-y-10">
            <h2 class="text-4xl md:text-7xl font-black tracking-tighter text-white dark:text-zinc-900 font-syne leading-tight">
                GABUNG <span class="italic font-normal text-indigo-400 dark:text-indigo-600">CIRCLE</span> <br> 
                PALING VALID.
            </h2>
            
            <p class="text-zinc-400 dark:text-zinc-500 text-base md:text-xl max-w-xl mx-auto leading-relaxed font-medium">
                Dapatkan akses ke Hidden Gems, review jujur, dan komunitas paling valid se-Jakarta Selatan.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                {{-- Button Rounded & Transparent Hover --}}
                <a href="{{ route('register') }}" wire:navigate class="w-full sm:w-auto px-10 py-4 rounded-full font-black text-lg transition-all duration-300 shadow-2xl border-2 
                    bg-white text-zinc-900 border-transparent 
                    hover:bg-transparent hover:text-white hover:border-white
                    
                    dark:bg-zinc-900 dark:text-white dark:border-transparent
                    dark:hover:bg-transparent dark:hover:text-zinc-900 dark:hover:border-zinc-900">
                    JOIN SEKARANG 🚀
                </a>
            </div>
        </div>
    </section>
</div>