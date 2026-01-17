<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Kalcer.id' }}</title>
        
        {{-- 1. SCRIPT DARK MODE UTAMA (Jalan saat Refresh/First Load) --}}
        <script>
            function applyTheme() {
                // Cek LocalStorage atau Preferensi Sistem
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
            // Jalankan segera
            applyTheme();
        </script>

        {{-- Mapbox --}}
        <script src='https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.css' rel='stylesheet' />
        
        {{-- Fonts & Icons --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            h1, h2, h3, .font-syne { font-family: 'Syne', sans-serif; }
            .mapboxgl-map { min-height: 100%; height: 100%; width: 100%; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    
    <body class="min-h-screen bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 flex flex-col transition-colors duration-300"
          x-data="{ 
              mobileMenuOpen: false, 
              // Inisialisasi state Alpine sesuai kondisi saat ini
              darkMode: document.documentElement.classList.contains('dark'),
              
              toggleTheme() {
                  this.darkMode = !this.darkMode;
                  localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                  
                  if (this.darkMode) {
                      document.documentElement.classList.add('dark');
                  } else {
                      document.documentElement.classList.remove('dark');
                  }
              }
          }"
          {{-- 2. EVENT LISTENER KHUSUS WIRE:NAVIGATE --}}
          {{-- Ini memaksa tema dicek ulang setiap kali pindah halaman --}}
          x-on:livewire:navigated.window="applyTheme(); darkMode = document.documentElement.classList.contains('dark')"
    >
        
        <nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-lg border-b border-zinc-200 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <a href="{{ route('home') }}" wire:navigate class="text-2xl font-black font-syne tracking-tighter flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-indigo-600"></i>
                            <span>Kalcer<span class="text-indigo-600">.id</span></span>
                        </a>
                    </div>
    
                    <div class="hidden md:flex items-center gap-2">
                        @php
                            $navLinkClass = "relative px-5 py-2.5 rounded-full font-bold text-sm transition-all duration-300 flex items-center gap-2 group hover:scale-105";
                            $activeClass = "bg-zinc-100 dark:bg-white/10 text-indigo-600 dark:text-indigo-400 shadow-lg shadow-indigo-500/10 backdrop-blur-md border border-zinc-200 dark:border-white/10";
                            $inactiveClass = "text-zinc-500 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-50 dark:hover:bg-white/5";
                        @endphp

                        <a href="{{ route('explore') }}" wire:navigate class="{{ $navLinkClass }} {{ request()->routeIs('explore') ? $activeClass : $inactiveClass }}">
                            <i class="fa-solid fa-compass {{ request()->routeIs('explore') ? 'animate-spin-slow' : '' }}"></i>
                            Explore
                        </a>

                        <a href="{{ route('maps') }}" wire:navigate class="{{ $navLinkClass }} {{ request()->routeIs('maps') ? $activeClass : $inactiveClass }}">
                            <i class="fa-solid fa-map-location-dot {{ request()->routeIs('maps') ? 'animate-bounce' : '' }}"></i>
                            Maps
                        </a>

                        <a href="{{ route('trending') }}" wire:navigate class="{{ $navLinkClass }} {{ request()->routeIs('trending') ? $activeClass : $inactiveClass }}">
                            <i class="fa-solid fa-fire-flame-curved {{ request()->routeIs('trending') ? 'text-orange-500 animate-pulse' : '' }}"></i>
                            Trending
                        </a>
                        
                        @auth
                            <div class="relative ml-4" x-data="{ open: false }">
                                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 font-bold hover:text-indigo-600 transition pl-4 border-l border-zinc-200 dark:border-zinc-700">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 p-[2px]">
                                        <div class="w-full h-full rounded-full bg-white dark:bg-zinc-900 flex items-center justify-center text-xs font-black text-zinc-700 dark:text-zinc-200">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <span class="text-sm">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition.opacity
                                     x-cloak
                                     class="absolute right-0 mt-4 w-56 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-800 py-2 overflow-hidden z-50">
                                    
                                    <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50">
                                        <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">Signed in as</p>
                                        <p class="font-bold truncate text-sm">{{ Auth::user()->email }}</p>
                                    </div>

                                    <div class="p-2 space-y-1">
                                        <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 text-sm font-medium transition">
                                            <i class="fa-solid fa-user-astronaut text-zinc-400"></i> Edit Profile
                                        </a>

                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('business.dashboard') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 text-sm font-bold text-red-500 transition">
                                                <i class="fa-solid fa-shield-cat"></i> Admin Panel
                                            </a>
                                        @endif
                                        
                                        @if(Auth::user()->role === 'business_owner')
                                            <a href="{{ route('business.index') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-sm font-bold text-indigo-600 transition">
                                                <i class="fa-solid fa-shop"></i> Bisnis Saya
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <div class="border-t border-zinc-100 dark:border-zinc-800 mt-1 p-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-3 px-3 py-2 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 text-sm font-bold transition">
                                                <i class="fa-solid fa-power-off"></i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="ml-4 px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold rounded-full hover:scale-105 hover:shadow-lg hover:shadow-indigo-500/20 transition duration-300">
                                Login
                            </a>
                        @endauth
    
                        <button @click="toggleTheme()" class="ml-2 w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center hover:bg-zinc-200 transition active:scale-90">
                            <i class="fa-solid" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-zinc-600'"></i>
                        </button>
                    </div>
    
                    <div class="flex items-center gap-4 md:hidden">
                        <button @click="toggleTheme()" class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="fa-solid" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-zinc-600'"></i>
                        </button>
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-2xl text-zinc-900 dark:text-white p-2 transition-transform" :class="mobileMenuOpen ? 'rotate-90' : ''">
                            <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                        </button>
                    </div>
                </div>
            </div>
    
            <div x-show="mobileMenuOpen" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-5"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-5"
                 class="md:hidden absolute top-16 left-0 w-full bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 shadow-2xl overflow-y-auto max-h-[80vh]">
                
                <div class="px-4 py-6 space-y-4">
                    <a href="{{ route('explore') }}" wire:navigate class="flex items-center gap-3 text-lg font-bold hover:text-indigo-600 p-2 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <i class="fa-solid fa-compass w-6 text-center text-zinc-400"></i> Explore List
                    </a>
                    <a href="{{ route('maps') }}" wire:navigate class="flex items-center gap-3 text-lg font-bold hover:text-indigo-600 p-2 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <i class="fa-solid fa-map-location-dot w-6 text-center text-zinc-400"></i> Maps
                    </a>
                    <a href="{{ route('trending') }}" wire:navigate class="flex items-center gap-3 text-lg font-bold hover:text-indigo-600 p-2 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <i class="fa-solid fa-fire w-6 text-center text-zinc-400"></i> Trending Spots
                    </a>
                    
                    <hr class="border-zinc-200 dark:border-zinc-700">
    
                    @auth
                        <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-4 border border-zinc-100 dark:border-zinc-700">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-zinc-500">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <a href="{{ route('profile.edit') }}" wire:navigate class="block w-full text-left py-2 px-3 rounded-lg hover:bg-white dark:hover:bg-zinc-700 text-sm font-medium transition">
                                    <i class="fa-solid fa-user-gear mr-2 text-zinc-400"></i> Edit Profile
                                </a>

                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('business.dashboard') }}" wire:navigate class="block w-full text-left py-2 px-3 rounded-lg hover:bg-white dark:hover:bg-zinc-700 text-sm font-medium text-red-500 transition">
                                        <i class="fa-solid fa-gauge mr-2"></i> Admin Panel
                                    </a>
                                @endif
                                
                                @if(Auth::user()->role === 'business_owner')
                                     <a href="{{ route('business.index') }}" wire:navigate class="block w-full text-left py-2 px-3 rounded-lg hover:bg-white dark:hover:bg-zinc-700 text-sm font-medium text-indigo-600 transition">
                                        <i class="fa-solid fa-store mr-2"></i> Dashboard Bisnis
                                     </a>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 font-bold text-red-500 text-sm">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="block w-full text-center py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg">
                            Masuk / Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="flex-grow relative w-full pt-16">
            <div class="fixed inset-0 z-[-1] opacity-20 pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>
            {{ $slot }}
        </main>

        <footer class="text-center text-xs text-zinc-500 py-6 border-t border-zinc-200 dark:border-zinc-800">
            <p>&copy; {{ date('Y') }} Kalcer.id. All rights reserved.</p>
        </footer>

    </body>
</html>