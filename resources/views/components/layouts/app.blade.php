<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark',
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
      class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Kalcer.id' }}</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
        
        <script src='https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.css' rel='stylesheet' />
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            h1, h2, h3, .brand-font { font-family: 'Syne', sans-serif; }
            .glass-nav {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
            .dark .glass-nav {
                background: rgba(15, 23, 42, 0.8);
            }
        </style>
    </head>
    
    @php
        $isBusiness = auth()->check() && auth()->user()->role === 'business_owner';
    @endphp
    
    <body class="antialiased transition-colors duration-300 bg-gray-50 text-gray-900 dark:bg-slate-950 dark:text-gray-100 min-h-screen flex flex-col">
        
        <nav class="fixed top-4 left-4 right-4 z-50 rounded-2xl border border-gray-200 dark:border-white/10 glass-nav shadow-lg shadow-gray-200/50 dark:shadow-none transition-all duration-300">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group" wire:navigate>
                        <div class="w-10 h-10 rounded-xl bg-black dark:bg-white text-white dark:text-black flex items-center justify-center font-black text-xl transform group-hover:rotate-12 transition shadow-lg">
                            K.
                        </div>
                        <div class="flex flex-col">
                            <span class="brand-font font-bold text-lg leading-none tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                                kalcer.id
                            </span>
                            @if($isBusiness)
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-none">Business</span>
                            @endif
                        </div>
                    </a>

                    <div class="hidden md:flex items-center gap-1 p-1 bg-gray-100 dark:bg-white/5 rounded-full border border-gray-200 dark:border-white/5">
                        <a href="{{ route('home') }}" class="px-5 py-2 rounded-full text-sm font-bold transition {{ request()->routeIs('home') ? 'bg-white dark:bg-slate-800 shadow-sm text-black dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white' }}" wire:navigate>
                            Home
                        </a>
                        <a href="{{ route('maps') }}" class="px-5 py-2 rounded-full text-sm font-bold transition {{ request()->routeIs('maps') ? 'bg-white dark:bg-slate-800 shadow-sm text-black dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white' }}" wire:navigate>
                            Maps
                        </a>
                        <a href="{{ route('trending') }}" class="px-5 py-2 rounded-full text-sm font-bold transition {{ request()->routeIs('trending') ? 'bg-white dark:bg-slate-800 shadow-sm text-black dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white' }}" wire:navigate>
                            Trending
                        </a>
                        
                        @if($isBusiness)
                            <a href="{{ route('business.dashboard') }}" class="px-5 py-2 rounded-full text-sm font-bold transition {{ request()->routeIs('business.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}" wire:navigate>
                                Dashboard
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                <span class="text-xl">{{ app()->getLocale() == 'id' ? '🇮🇩' : '🇺🇸' }}</span>
                            </button>
                            
                            <div x-show="open" 
                                 class="absolute right-0 mt-2 w-32 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden z-50"
                                 style="display: none;">
                                <a href="{{ route('lang.switch', 'id') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-slate-800 items-center gap-2">
                                    🇮🇩 Indo
                                </a>
                                <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-slate-800 items-center gap-2">
                                    🇺🇸 English
                                </a>
                            </div>
                        </div>

                        <button @click="toggleTheme()" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/10 transition text-gray-600 dark:text-gray-300">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>

                        @auth
                            <div x-data="{ open: false }" class="relative ml-2">
                                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 transition">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="text-xs font-bold hidden sm:block max-w-[80px] truncate">
                                        {{ auth()->user()->username ?? explode(' ', auth()->user()->name)[0] }}
                                    </span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-56 origin-top-right rounded-2xl shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden z-50 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 text-gray-700 dark:text-gray-300"
                                     style="display: none;">
                                    
                                    <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-800">
                                        <p class="text-xs opacity-50 uppercase tracking-wider">Signed in as</p>
                                        <p class="text-sm font-bold truncate text-black dark:text-white">{{ auth()->user()->email }}</p>
                                    </div>

                                    <div class="py-1">
                                        @if($isBusiness)
                                            <a href="{{ route('business.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 transition">
                                                <span>📊</span> Dashboard
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                            <span>👤</span> My Profile
                                        </a>
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                            <span>⚙️</span> Settings
                                        </a>
                                    </div>

                                    <div class="border-t border-gray-100 dark:border-slate-800">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                                <span>🚪</span> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-black dark:bg-white text-white dark:text-black font-bold text-sm hover:scale-105 transition shadow-lg ml-2">
                                Join
                            </a>
                        @endauth

                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-28 pb-12 px-4 relative">
            <div class="fixed inset-0 z-[-1] opacity-30 pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>
            <div class="fixed top-0 left-0 w-96 h-96 bg-purple-500/20 rounded-full blur-[100px] pointer-events-none z-[-1]"></div>
            <div class="fixed bottom-0 right-0 w-96 h-96 bg-pink-500/20 rounded-full blur-[100px] pointer-events-none z-[-1]"></div>

            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 dark:border-white/10 bg-white/50 dark:bg-black/50 backdrop-blur-lg py-12 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <h2 class="brand-font text-2xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400">kalcer.id</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Curated hidden gems for your next hangout.</p>
                <div class="mt-8 text-xs text-gray-400">
                    &copy; {{ date('Y') }} Kalcer.ID. All rights reserved.
                </div>
            </div>
        </footer>
    </body>
</html>