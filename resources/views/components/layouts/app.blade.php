<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Kalcer.id' }}</title>
        
        {{-- 1. SCRIPT GLOBAL THEME --}}
        <script>
            function updateTheme() {
                const isDark = localStorage.theme === 'dark' || 
                    (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
            updateTheme();
            document.addEventListener('livewire:navigated', updateTheme);
        </script>

        {{-- Assets --}}
        <script src='https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.css' rel='stylesheet' />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
            .mapboxgl-map { min-height: 100%; height: 100%; width: 100%; }
        </style>
    </head>

    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-800 dark:text-zinc-200 flex flex-col transition-colors duration-300"
        x-data="{ 
            mobileMenuOpen: false, 
            darkMode: document.documentElement.classList.contains('dark'),
            toggleTheme() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                updateTheme();
            }
        }"
        x-on:livewire:navigated.window="darkMode = document.documentElement.classList.contains('dark')"
    >
        
        {{-- NAVBAR FLOATING ISLAND --}}
        <nav class="fixed top-5 left-1/2 -translate-x-1/2 w-[95%] max-w-7xl z-50 
                    bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl 
                    border border-zinc-200/50 dark:border-white/10 
                    rounded-full shadow-2xl shadow-zinc-200/50 dark:shadow-black/50 
                    transition-all duration-300">
            
            <div class="px-6 sm:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    {{-- LOGO --}}
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <a href="{{ route('home') }}" wire:navigate class="text-2xl font-black font-syne tracking-tighter flex items-center gap-2 group">
                            <i class="fa-solid fa-layer-group text-indigo-600 group-hover:rotate-12 transition-transform"></i>
                            <span>Kalcer<span class="text-indigo-600">.id</span></span>
                        </a>
                    </div>
    
                    {{-- DESKTOP MENU --}}
                    <div class="hidden md:flex items-center gap-1">
                        @php
                            // Update style link agar lebih clean di dalam floating nav
                            $navLinkClass = "relative px-4 py-2 rounded-full font-bold text-sm transition-all duration-300 flex items-center gap-2 hover:bg-zinc-100 dark:hover:bg-white/10";
                            $activeClass = "text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10";
                            $inactiveClass = "text-zinc-500 hover:text-zinc-900 dark:hover:text-white";
                        @endphp

                        <a href="{{ route('explore') }}" wire:navigate class="{{ $navLinkClass }} {{ request()->routeIs('explore') ? $activeClass : $inactiveClass }}">
                            Explore
                        </a>

                        <a href="{{ route('maps') }}" wire:navigate class="{{ $navLinkClass }} {{ request()->routeIs('maps') ? $activeClass : $inactiveClass }}">
                            Maps
                        </a>

                        <a href="{{ route('trending') }}" wire:navigate class="{{ $navLinkClass }} {{ request()->routeIs('trending') ? $activeClass : $inactiveClass }}">
                            Trending
                        </a>
                        
                        {{-- Separator --}}
                        <div class="h-6 w-px bg-zinc-200 dark:bg-white/10 mx-2"></div>

                        @auth
                            {{-- Profile Dropdown --}}
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 font-bold hover:text-indigo-600 transition pl-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 p-[2px]">
                                        <div class="w-full h-full rounded-full bg-white dark:bg-zinc-900 flex items-center justify-center text-[10px] font-black text-zinc-700 dark:text-zinc-200 uppercase">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                
                                <div x-show="open" x-transition.opacity x-cloak class="absolute right-0 mt-4 w-56 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-800 py-2 overflow-hidden z-50">
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

                            @livewire('layout.notification-bell')

                        @else
                            <a href="{{ route('login') }}" wire:navigate class="ml-2 px-5 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold rounded-full hover:scale-105 transition duration-300 shadow-lg text-sm">
                                Login
                            </a>
                        @endauth
    
                        {{-- TOGGLE THEME BUTTON --}}
                        <button @click="toggleTheme()" class="ml-2 w-9 h-9 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center hover:bg-zinc-200 dark:hover:bg-zinc-700 transition active:scale-90 shadow-sm border border-transparent dark:border-white/5">
                            <i class="fa-solid fa-sun text-yellow-400 text-sm transition-transform duration-500" x-show="darkMode" style="display: none;"></i>
                            <i class="fa-solid fa-moon text-zinc-600 text-sm transition-transform duration-500" x-show="!darkMode"></i>
                        </button>
                    </div>
    
                    {{-- MOBILE TOGGLE & MENU --}}
                    <div class="flex items-center gap-3 md:hidden">
                        <button @click="toggleTheme()" class="w-9 h-9 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            <i class="fa-solid" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-zinc-600'"></i>
                        </button>
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-xl text-zinc-900 dark:text-white p-2">
                            <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                        </button>
                    </div>
                </div>
            </div>
    
            {{-- MOBILE MENU DROPDOWN --}}
            <div x-show="mobileMenuOpen" 
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                class="md:hidden absolute top-20 left-0 w-full px-4">
                
                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-2xl p-4 space-y-2">
                    <a href="{{ route('explore') }}" wire:navigate class="block px-4 py-3 rounded-xl font-bold hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        Explore
                    </a>
                    <a href="{{ route('maps') }}" wire:navigate class="block px-4 py-3 rounded-xl font-bold hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        Maps
                    </a>
                    <a href="{{ route('trending') }}" wire:navigate class="block px-4 py-3 rounded-xl font-bold hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        Trending
                    </a>

                    <div class="border-t border-zinc-100 dark:border-zinc-800 my-2 pt-2">
                        @auth
                            <div class="px-4 py-2 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-sm">{{ Auth::user()->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 rounded-xl text-red-500 font-bold hover:bg-red-50 dark:hover:bg-red-900/10">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="block w-full text-center py-3 bg-zinc-900 dark:bg-white text-white dark:text-black font-bold rounded-xl">
                                Masuk Akun
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- MAIN CONTENT --}}
        {{-- Added padding-top 32 (pt-32) to compensate for the floating navbar --}}
        <main class="flex-grow relative w-full pt-32">
            {{ $slot }}
        </main>

        <footer class="text-center text-xs text-zinc-500 py-8 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 mt-auto">
            <p>&copy; {{ date('Y') }} Kalcer.id. Jakarta Selatan Pride.</p>
        </footer>

    </body>
</html>