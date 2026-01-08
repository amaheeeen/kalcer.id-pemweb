<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Kalcer.id' }}</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    {{-- LOGIC TEMA: Hitam untuk Business Owner, Putih/Cerah untuk User --}}
    @php
        $isBusiness = auth()->check() && auth()->user()->role === 'business_owner';
        
        $bodyClass = $isBusiness 
            ? 'bg-slate-900 text-gray-100 font-sans antialiased' 
            : 'bg-gradient-to-br from-purple-50 via-white to-pink-50 text-gray-900 font-sans antialiased';

        $navClass = $isBusiness
            ? 'bg-slate-900/90 border-slate-700 text-white'
            : 'bg-white/90 border-white/20 text-gray-900';
            
        $logoColor = $isBusiness 
            ? 'text-amber-400' 
            : 'bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent';
    @endphp

    <body class="{{ $bodyClass }} min-h-screen flex flex-col">
        
        <nav class="fixed top-0 w-full z-50 transition-all duration-300 backdrop-blur-md border-b shadow-sm {{ $navClass }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group" wire:navigate>
                        <span class="font-bold text-xl tracking-tight {{ $logoColor }}">
                            kalcer.id {{ $isBusiness ? 'Business' : '' }}
                        </span>
                    </a>

                    <div class="hidden md:flex space-x-8">
                        @if($isBusiness)
                            <a href="{{ route('business.dashboard') }}" class="hover:text-amber-400 font-medium transition" wire:navigate>Dashboard</a>
                        @else
                            <a href="{{ route('home') }}" class="hover:text-purple-600 font-medium transition" wire:navigate>Home</a>
                            <a href="{{ route('maps') }}" class="hover:text-purple-600 font-medium transition" wire:navigate>Maps</a>
                            <a href="{{ route('trending') }}" class="hover:text-purple-600 font-medium transition" wire:navigate>Trending</a>
                            <a href="{{ route('about') }}" class="hover:text-purple-600 font-medium transition" wire:navigate>About Us</a>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        @auth
                            <div class="relative group">
                                <button class="flex items-center gap-2 font-bold text-sm">
                                    {{ substr(auth()->user()->name, 0, 10) }} ▼
                                </button>
                                
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border overflow-hidden hidden group-hover:block text-gray-800">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Dashboard</a>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium text-sm hover:shadow-lg transition">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-20">
            {{ $slot }}
        </main>

        <footer class="{{ $isBusiness ? 'bg-slate-900 border-slate-700' : 'bg-white border-gray-100' }} border-t py-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} Kalcer.ID. Kurasi Anak Jaksel.
            </div>
        </footer>
    </body>
</html>