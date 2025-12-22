<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    public function with(): array
    {
        return [
            // DATA DUMMY: Berdasarkan Riset Tren Jaksel Late 2025
            'recommendations' => [
                [
                    'id' => 1,
                    'name' => 'Tanatap Coffee Ampera',
                    'category' => 'Artistic & WFC',
                    'image' => 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=800&auto=format&fit=crop', // Representasi: Green Architecture
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
                    'image' => 'https://images.unsplash.com/photo-1620916297397-a4a5402a3c6c?q=80&w=800&auto=format&fit=crop', // LINK BARU (Nuansa Taman Kota)
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
                    'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=800&auto=format&fit=crop', // Representasi: Modern Minimalist
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

<div class="min-h-screen bg-white font-sans text-gray-900">
    
    <section class="relative h-[85vh] min-h-[600px] flex items-center justify-center overflow-hidden bg-black">
        
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1534353473418-4cfa6c56fd38?q=80&w=2000&auto=format&fit=crop" 
                 class="w-full h-full object-cover object-center scale-105 animate-slow-zoom opacity-60"
                 alt="Jakarta Night Cityscape">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent"></div>
        </div>
        
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto space-y-8 animate-fade-in-up">
            
            <div class="flex justify-center">
                <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white/10 backdrop-blur-lg border border-white/10 text-white text-xs font-bold tracking-[0.2em] uppercase">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Live Jakarta Selatan Guide
                </span>
            </div>

            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white tracking-tighter leading-none drop-shadow-2xl">
                Temukan <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-500 to-red-500">Vibes</span><br>
                Jakarta Selatan.
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto leading-relaxed font-medium drop-shadow-md">
                Kurasi tempat nongkrong paling valid, anti-zonk, dan terupdate real-time. Dari hidden gem Senopati sampai rooftop hits SCBD.
            </p>
            
            <div class="pt-8 flex flex-col sm:flex-row gap-5 justify-center">
                <a href="{{ route('maps') }}" wire:navigate class="group relative px-8 py-4 bg-white text-black rounded-full font-bold text-lg overflow-hidden shadow-[0_0_30px_rgba(255,255,255,0.3)] transition-all hover:scale-105 hover:shadow-[0_0_50px_rgba(255,255,255,0.5)]">
                    <span class="relative z-10 flex items-center gap-2">
                        Jelajahi Peta
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </span>
                </a>
                <a href="{{ route('trending') }}" wire:navigate class="group px-8 py-4 bg-white/5 backdrop-blur-md border-2 border-white/30 text-white rounded-full font-bold text-lg transition-all hover:bg-white/20 hover:border-white">
                    Lihat Trending 🔥
                </a>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-white/50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </section>

    <section class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">
                    Rekomendasi Minggu Ini ✨
                </h2>
                <p class="text-gray-500 text-lg">Spot paling <span class="text-purple-600 font-bold">valid</span> menurut algoritma Jaksel.</p>
            </div>
            <a href="{{ route('trending') }}" wire:navigate class="hidden md:flex items-center gap-2 text-sm font-bold text-purple-600 hover:text-purple-800 transition">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($recommendations as $place)
                <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 overflow-hidden flex flex-col h-full cursor-pointer">
                    
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $place['image'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur text-xs font-extrabold uppercase tracking-wide rounded-full text-gray-900 shadow-sm">
                                {{ $place['badge'] }}
                            </span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <div class="flex items-center gap-1 bg-black/70 backdrop-blur text-white px-2 py-1 rounded-lg text-xs font-bold">
                                ⭐ {{ $place['rating'] }} <span class="text-gray-400 font-normal">({{ $place['reviews'] }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-purple-600 uppercase tracking-wider bg-purple-50 px-2 py-1 rounded-md">
                                {{ $place['category'] }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition">
                            {{ $place['name'] }}
                        </h3>
                        
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-4 flex-1">
                            {{ $place['description'] }}
                        </p>
                        
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <div class="flex items-center text-gray-400 text-xs font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $place['location'] }}
                            </div>
                            <span class="text-purple-600 font-bold text-sm">Detail →</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('trending') }}" wire:navigate class="inline-flex items-center justify-center w-full px-6 py-3 border border-gray-300 shadow-sm text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50">
                Lihat Semua Tempat
            </a>
        </div>
    </section>

    <section class="py-20 bg-gray-50 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold mb-12">Kenapa Kalcer.ID?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-md mx-auto flex items-center justify-center text-3xl">🎯</div>
                    <h3 class="font-bold text-xl">Akurasi Tinggi</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Kami memfilter tempat yang cuma "bagus di foto" tapi aslinya zonk. Review jujur adalah kunci.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-md mx-auto flex items-center justify-center text-3xl">⚡</div>
                    <h3 class="font-bold text-xl">Update Tiap Hari</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Bot kami memantau tren TikTok & Instagram secara real-time untuk menangkap apa yang lagi viral.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-md mx-auto flex items-center justify-center text-3xl">🤝</div>
                    <h3 class="font-bold text-xl">Komunitas</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Dibangun oleh anak Jaksel, untuk anak Jaksel. Bergabunglah dan bagikan hidden gem versimu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-32 px-4 overflow-hidden bg-black text-white text-center">
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-gradient-to-r from-purple-900/40 via-pink-900/40 to-purple-900/40 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 max-w-4xl mx-auto space-y-8">
            <h2 class="text-4xl md:text-6xl font-black tracking-tighter">
                Jangan Cuma Jadi <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500">Penonton Story.</span>
            </h2>
            
            <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                Gabung sekarang buat dapet akses ke <strong>Hidden Gems</strong>, review jujur, dan komunitas paling valid se-Jakarta Selatan.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center pt-6">
                <a href="{{ route('register') }}" class="px-10 py-4 bg-white text-black rounded-full font-bold text-lg hover:bg-gray-200 transition transform hover:-translate-y-1 shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_40px_rgba(255,255,255,0.5)]">
                    Join Community 🚀
                </a>
                
                <a href="{{ route('login') }}" class="px-10 py-4 bg-transparent border border-gray-700 text-white rounded-full font-bold text-lg hover:border-white hover:bg-white/5 transition">
                    Masuk Akun
                </a>
            </div>
            
            <p class="text-xs text-gray-600 pt-8 uppercase tracking-widest font-bold">
                Join 1,200+ Local Explorers
            </p>
        </div>
    </section>
</div>