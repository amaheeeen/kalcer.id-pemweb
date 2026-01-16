<?php

use App\Models\HangoutPlace;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    public function with(): array
    {
        return [
            'places' => HangoutPlace::query()
                ->where('is_verified', true)
                ->orderBy('viral_score', 'desc')
                ->take(10)
                ->get(),
        ];
    }
}; ?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 transition-colors duration-300 pb-20">
    
    <div class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800 transition-colors">
        <div class="max-w-7xl mx-auto py-16 px-4 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold tracking-widest mb-4 animate-pulse">
                LIVE UPDATES 🔴
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-zinc-900 dark:text-white tracking-tight mb-4">
                Jaksel <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">Trending Chart</span>
            </h1>
            <p class="text-xl text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto">
                Algoritma kami memantau TikTok FYP & Instagram Explore secara real-time. Kalau masuk list ini, berarti valid. No debat.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        
        <div class="space-y-6">
            @foreach($places as $index => $place)
                <div class="group relative bg-white dark:bg-zinc-800 rounded-2xl shadow-sm hover:shadow-xl dark:hover:shadow-purple-900/20 hover:-translate-y-1 transition duration-300 border border-gray-100 dark:border-zinc-700 overflow-hidden">
                    
                    <div class="absolute -left-4 -top-6 text-[150px] font-black text-gray-50 dark:text-zinc-700/30 opacity-50 select-none z-0 pointer-events-none group-hover:text-purple-50 dark:group-hover:text-purple-900/10 transition">
                        #{{ $index + 1 }}
                    </div>

                    <div class="relative z-10 flex flex-col md:flex-row">
                        
                        <div class="w-full md:w-72 h-64 md:h-auto relative shrink-0">
                            <img src="{{ $place->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            
                            <div class="absolute top-4 left-4 flex gap-2">
                                @if($index === 0)
                                    <span class="bg-yellow-400 text-black text-xs font-bold px-3 py-1 rounded-full shadow-lg flex items-center gap-1">
                                        👑 KING OF JAKSEL
                                    </span>
                                @elseif($index < 3)
                                    <span class="bg-white/90 backdrop-blur text-purple-700 text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        🔥 TOP {{ $index + 1 }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex-1 p-6 md:p-8 flex flex-col justify-center">
                            
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider">{{ $place->category }}</span>
                                        
                                        @if($place->viral_score > 90)
                                            <span class="flex items-center gap-1 text-green-600 dark:text-green-400 text-xs font-bold bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                                                <i class="fa-solid fa-arrow-trend-up"></i> Trending Up
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1 text-yellow-600 dark:text-yellow-400 text-xs font-bold bg-yellow-50 dark:bg-yellow-900/30 px-2 py-0.5 rounded-full">
                                                <i class="fa-solid fa-minus"></i> Stable
                                            </span>
                                        @endif
                                    </div>
                                    <h2 class="text-2xl md:text-3xl font-extrabold text-zinc-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                                        <a href="{{ route('place.show', $place) }}" wire:navigate>{{ $place->name }}</a>
                                    </h2>
                                    <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-2 line-clamp-2">{{ $place->description }}</p>
                                </div>

                                <div class="hidden md:flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full border-4 border-purple-100 dark:border-zinc-700 flex items-center justify-center bg-white dark:bg-zinc-800 shadow-sm">
                                        <span class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-br from-purple-600 to-pink-600">
                                            {{ $place->viral_score }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] font-bold text-zinc-400 mt-1 uppercase">Score</span>
                                </div>
                            </div>

                            <div class="bg-zinc-50 dark:bg-zinc-700/30 rounded-xl p-4 border border-zinc-100 dark:border-zinc-700 flex flex-wrap gap-6 items-center">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-black dark:bg-white text-white dark:text-black flex items-center justify-center">
                                        <i class="fa-brands fa-tiktok text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ number_format($place->viral_score * 1200) }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">Views</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-500 text-white flex items-center justify-center">
                                        <i class="fa-brands fa-instagram text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ number_format($place->viral_score * 850) }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">Mentions</div>
                                    </div>
                                </div>

                                <div class="ml-auto">
                                    <a href="{{ route('place.show', $place) }}" class="text-sm font-bold text-purple-600 dark:text-purple-400 hover:text-pink-600 dark:hover:text-pink-400 transition" wire:navigate>
                                        Analisa Detail →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>