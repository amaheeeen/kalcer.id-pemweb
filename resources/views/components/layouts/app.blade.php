<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Kalcer.id' }}</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    
    @php
        $isBusiness = auth()->check() && auth()->user()->role === 'business_owner';
        // Background dinamis
        $bodyClass = $isBusiness 
            ? 'bg-slate-950 text-gray-100 antialiased' 
            : 'bg-gray-50 text-gray-900 antialiased';

        $navClass = $isBusiness
            ? 'bg-slate-900/80 border-slate-800 text-white backdrop-blur-xl'
            : 'bg-white/80 border-gray-200 text-gray-900 backdrop-blur-xl';
    @endphp

    <body class="{{ $bodyClass }} min-h-screen flex flex-col">
        
        <nav class="fixed top-0 w-full z-50 transition-all duration-300 border-b {{ $navClass }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group" wire:navigate>
                        <span class="font-extrabold text-2xl tracking-tight bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent group-hover:scale-105 transition-transform">
                            kalcer.id
                        </span>
                        @if($isBusiness)
                            <span class="px-2 py-0.5 rounded-full bg-slate-800 border border-slate-700 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Business</span>
                        @endif
                    </a>

                    <div class="hidden md:flex space-x-1 items-center">
                        {{-- Menu Standar (Muncul untuk semua role agar Business Owner juga bisa liat maps) --}}
                        <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-100/10 transition {{ $isBusiness ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}" wire:navigate>Home</a>
                        <a href="{{ route('maps') }}" class="px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-100/10 transition {{ $isBusiness ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}" wire:navigate>Maps</a>
                        <a href="{{ route('trending') }}" class="px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-100/10 transition {{ $isBusiness ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}" wire:navigate>Trending</a>

                        @if($isBusiness)
                            <div class="h-4 w-px bg-slate-700 mx-2"></div>
                            <a href="{{ route('business.dashboard') }}" class="px-4 py-2 rounded-full text-sm font-bold bg-indigo-600 text-white hover:bg-indigo-500 shadow-lg shadow-indigo-500/20 transition" wire:navigate>
                                Dashboard
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        @auth
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 pl-2 pr-1 py-1 rounded-full border transition {{ $isBusiness ? 'border-slate-700 hover:bg-slate-800' : 'border-gray-200 hover:bg-white shadow-sm' }}">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shadow-inner">
                                        {{ substr(auth()->user()->name, 0, 2) }}
                                    </div>
                                    <span class="text-xs font-bold px-2 {{ $isBusiness ? 'text-gray-300' : 'text-gray-700' }}">
                                        {{ Str::limit(auth()->user()->name, 10) }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-56 origin-top-right rounded-2xl shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden z-50 {{ $isBusiness ? 'bg-slate-900 border border-slate-700 text-gray-200' : 'bg-white border border-gray-100 text-gray-700' }}"
                                     style="display: none;">
                                    
                                    <div class="px-4 py-3 border-b {{ $isBusiness ? 'border-slate-800' : 'border-gray-100' }}">
                                        <p class="text-xs opacity-50">Signed in as</p>
                                        <p class="text-sm font-bold truncate">{{ auth()->user()->email }}</p>
                                    </div>

                                    <div class="py-1">
                                        @if($isBusiness)
                                            <a href="{{ route('business.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-indigo-500/10 hover:text-indigo-500 transition">
                                                <span>📊</span> Dashboard Bisnis
                                            </a>
                                        @endif
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-purple-500/10 hover:text-purple-600 transition">
                                            <span>⚙️</span> Pengaturan Profil
                                        </a>
                                    </div>

                                    <div class="border-t {{ $isBusiness ? 'border-slate-800' : 'border-gray-100' }}">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition">
                                                <span>🚪</span> Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full bg-black text-white font-bold text-sm hover:scale-105 hover:shadow-lg transition duration-200">
                                Join Now
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-24 pb-12 px-4">
            {{ $slot }}
        </main>

        <footer class="{{ $isBusiness ? 'bg-slate-950 border-slate-800 text-slate-500' : 'bg-white border-gray-100 text-gray-400' }} border-t py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center text-sm">
                &copy; {{ date('Y') }} Kalcer.ID. <span class="mx-1">✨</span> Dibuat dengan cinta di Jaksel.
            </div>
        </footer>
    </body>
</html>