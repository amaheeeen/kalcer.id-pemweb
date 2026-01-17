<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Kalcer.id' }}</title>
        
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
              darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
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
          x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');"
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
    
                    <div class="hidden md:flex space-x-8 items-center">
                        <a href="{{ route('explore') }}" wire:navigate class="font-bold hover:text-indigo-600 transition {{ request()->routeIs('explore') ? 'text-indigo-600' : '' }}">
                            Explore
                        </a>
                        <a href="{{ route('maps') }}" wire:navigate class="font-bold hover:text-indigo-600 transition {{ request()->routeIs('maps') ? 'text-indigo-600' : '' }}">
                            Peta Sebaran
                        </a>
                        <a href="{{ route('trending') }}" wire:navigate class="font-bold hover:text-indigo-600 transition {{ request()->routeIs('trending') ? 'text-indigo-600' : '' }}">
                            Trending
                        </a>
                        
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 font-bold hover:text-indigo-600 transition">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-zinc-800 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold border border-zinc-200 dark:border-zinc-700">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }} 
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition.opacity
                                     x-cloak
                                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-zinc-800 rounded-xl shadow-xl border border-zinc-200 dark:border-zinc-700 py-2 overflow-hidden">
                                    
                                    <div class="px-4 py-2 border-b border-zinc-100 dark:border-zinc-700 mb-1">
                                        <p class="text-xs text-zinc-500">Signed in as</p>
                                        <p class="font-bold truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    {{-- Link Edit Profile --}}
                                    <a href="{{ route('profile.edit') }}" wire:navigate class="block px-4 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-sm font-medium">
                                        <i class="fa-solid fa-user-gear mr-2 text-zinc-400"></i> Edit Profile
                                    </a>

                                    {{-- LOGIKA MENU DIPISAH DI SINI --}}
                                    
                                    {{-- 1. Khusus Admin --}}
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('business.dashboard') }}" wire:navigate class="block px-4 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-sm font-medium text-red-500">
                                            <i class="fa-solid fa-gauge mr-2"></i> Admin Panel
                                        </a>
                                    @endif
                                    
                                    {{-- 2. Khusus Business Owner (Admin TIDAK AKAN LIHAT INI) --}}
                                    @if(Auth::user()->role === 'business_owner')
                                        <a href="{{ route('business.index') }}" wire:navigate class="block px-4 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-sm font-medium text-indigo-600">
                                            <i class="fa-solid fa-store mr-2"></i> Bisnis Saya
                                        </a>
                                    @endif
                                    
                                    <div class="border-t border-zinc-100 dark:border-zinc-700 mt-1"></div>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 text-sm font-bold">
                                            <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="px-5 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold rounded-full hover:scale-105 transition">
                                Login
                            </a>
                        @endauth
    
                        <button @click="toggleTheme()" class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center hover:bg-zinc-200 transition">
                            <i class="fa-solid" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-zinc-600'"></i>
                        </button>
                    </div>
    
                    <div class="flex items-center gap-4 md:hidden">
                        <button @click="toggleTheme()" class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="fa-solid" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-zinc-600'"></i>
                        </button>
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-2xl text-zinc-900 dark:text-white p-2">
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
                    <a href="{{ route('explore') }}" wire:navigate class="block text-lg font-bold hover:text-indigo-600">Explore List</a>
                    <a href="{{ route('maps') }}" wire:navigate class="block text-lg font-bold hover:text-indigo-600">Peta Sebaran</a>
                    <a href="{{ route('trending') }}" wire:navigate class="block text-lg font-bold hover:text-indigo-600">Trending Spots</a>
                    
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

                                {{-- PEMISAHAN LOGIKA DI MOBILE MENU JUGA --}}
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