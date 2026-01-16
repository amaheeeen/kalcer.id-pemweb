@php
    $user = auth()->user();
    $hasBusiness = $user ? $user->business : null;
    $isAdmin = $user && $user->role === 'admin'; // Cek apakah user adalah admin
@endphp

<header x-data="{ mobileMenuOpen: false, profileOpen: false }" class="sticky top-0 z-50 w-full border-b border-zinc-200 bg-white/80 dark:bg-zinc-900/80 dark:border-zinc-800 backdrop-blur-xl transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            
            <div class="flex items-center gap-4 lg:gap-8">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 group">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black font-syne shadow-lg shadow-indigo-500/30 group-hover:rotate-12 transition transform">K.</div>
                    <span class="hidden md:block font-syne font-bold text-lg tracking-tight text-zinc-900 dark:text-white">Kalcer<span class="text-indigo-500">.id</span></span>
                </a>

                <nav class="hidden lg:flex gap-6 text-sm font-bold text-zinc-500 dark:text-zinc-400">
                    <a href="{{ route('home') }}" wire:navigate class="hover:text-indigo-600 dark:hover:text-indigo-400 transition {{ request()->routeIs('home') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Home</a>
                    <a href="{{ route('explore') }}" wire:navigate class="hover:text-indigo-600 dark:hover:text-indigo-400 transition {{ request()->routeIs('explore') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Explore</a>
                    <a href="{{ route('maps') }}" wire:navigate class="hover:text-indigo-600 dark:hover:text-indigo-400 transition {{ request()->routeIs('maps') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Maps</a>
                    <a href="{{ route('trending') }}" wire:navigate class="hover:text-indigo-600 dark:hover:text-indigo-400 transition {{ request()->routeIs('trending') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Trending</a>
                </nav>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                
                @auth
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 rounded-lg bg-red-600 text-white shadow-lg shadow-red-500/30 hover:bg-red-500 transition mr-1">
                            <i class="fa-solid fa-shield-halved"></i> 
                            <span class="hidden sm:inline text-xs font-bold uppercase tracking-wide">Admin Panel</span>
                        </a>
                    @endif

                    @if($hasBusiness)
                        <a href="{{ route('business.index') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-500 transition mr-1">
                            <i class="fa-solid fa-store"></i> 
                            <span class="hidden sm:inline text-xs font-bold uppercase tracking-wide">My Business</span>
                        </a>
                    @endif
                @endauth

                <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-500 dark:text-zinc-400 transition">
                    <i class="fa-regular fa-moon hidden dark:block"></i>
                    <i class="fa-regular fa-sun block dark:hidden"></i>
                </button>

                @auth
                    <div class="relative ml-1">
                        <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" class="flex items-center gap-2 focus:outline-none group">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 p-[2px] group-hover:scale-105 transition">
                                <div class="w-full h-full rounded-full bg-zinc-900 flex items-center justify-center text-white font-bold text-xs uppercase">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            </div>
                        </button>

                        <div x-show="profileOpen" 
                             style="display: none;"
                             class="absolute right-0 mt-2 w-60 origin-top-right rounded-2xl bg-white dark:bg-zinc-900 shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none border border-zinc-100 dark:border-zinc-800 overflow-hidden z-50">
                            
                            <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
                                <p class="text-sm font-bold text-zinc-900 dark:text-white truncate">{{ $user->name }}</p>
                                <p class="text-xs text-zinc-500 truncate">{{ $user->email }}</p>
                                @if($isAdmin)
                                    <span class="mt-1 inline-block text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded font-bold uppercase">Administrator</span>
                                @elseif($hasBusiness)
                                    <span class="mt-1 inline-block text-[10px] bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded font-bold uppercase">Business Owner</span>
                                @endif
                            </div>

                            <div class="py-1">
                                @if($hasBusiness)
                                    <a href="{{ route('business.index') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/10 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition">
                                        <i class="fa-solid fa-gauge-high"></i> Dashboard Bisnis
                                    </a>
                                @else
                                    <a href="{{ route('business.create') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-green-600 dark:text-green-400 font-bold hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                        <i class="fa-solid fa-plus-circle"></i> Daftarkan Bisnis
                                    </a>
                                @endif

                                <a href="{{ route('wishlist') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                    <i class="fa-solid fa-heart text-pink-500"></i> My Wishlist
                                </a>
                                
                                <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                    <i class="fa-solid fa-gear text-zinc-400"></i> Settings
                                </a>
                            </div>

                            <div class="border-t border-zinc-100 dark:border-zinc-800 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 transition">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-zinc-600 dark:text-zinc-300 hover:text-indigo-500 px-3 py-2">Log In</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded-xl shadow-lg shadow-indigo-500/30 transition">Sign Up</a>
                    </div>
                @endauth

            </div>
        </div>
    </div>
</header>