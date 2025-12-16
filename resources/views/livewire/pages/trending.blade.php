<?php

use App\Models\HangoutPlace;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    public function with(): array
    {
        // Ambil data diurutkan berdasarkan skor viral tertinggi
        return [
            'places' => HangoutPlace::query()
                ->where('is_verified', true)
                ->orderBy('viral_score', 'desc')
                ->take(10) // Top 10 Viral
                ->get(),
        ];
    }
}; ?>

<div class="min-h-screen bg-gray-50 pb-20">
    
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto py-16 px-4 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-red-100 text-red-600 text-xs font-bold tracking-widest mb-4 animate-pulse">
                LIVE UPDATES 🔴
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-4">
                Jaksel <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">Trending Chart</span>
            </h1>
            <p class="text-xl text-gray-500 max-w-2xl mx-auto">
                Algoritma kami memantau TikTok FYP & Instagram Explore secara real-time. Kalau masuk list ini, berarti valid. No debat.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        
        <div class="space-y-6">
            @foreach($places as $index => $place)
                <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-gray-100 overflow-hidden">
                    
                    <div class="absolute -left-4 -top-6 text-[150px] font-black text-gray-50 opacity-50 select-none z-0 pointer-events-none group-hover:text-purple-50 transition">
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
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $place->category }}</span>
                                        
                                        @if($place->viral_score > 90)
                                            <span class="flex items-center gap-1 text-green-600 text-xs font-bold bg-green-50 px-2 py-0.5 rounded-full">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                                Trending Up
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1 text-yellow-600 text-xs font-bold bg-yellow-50 px-2 py-0.5 rounded-full">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path></svg>
                                                Stable
                                            </span>
                                        @endif
                                    </div>
                                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 group-hover:text-purple-600 transition">
                                        <a href="{{ route('place.show', $place) }}" wire:navigate>{{ $place->name }}</a>
                                    </h2>
                                    <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $place->description }}</p>
                                </div>

                                <div class="hidden md:flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full border-4 border-purple-100 flex items-center justify-center bg-white shadow-sm">
                                        <span class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-br from-purple-600 to-pink-600">
                                            {{ $place->viral_score }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-400 mt-1 uppercase">Score</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-wrap gap-6 items-center">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ number_format($place->viral_score * 1200) }}</div>
                                        <div class="text-xs text-gray-500">Views</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-500 text-white flex items-center justify-center">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.069-3.204 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.069-4.849 0-3.204.013-3.583.069-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ number_format($place->viral_score * 850) }}</div>
                                        <div class="text-xs text-gray-500">Mentions</div>
                                    </div>
                                </div>

                                <div class="ml-auto">
                                    <a href="{{ route('place.show', $place) }}" class="text-sm font-bold text-purple-600 hover:text-pink-600 transition" wire:navigate>
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