<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="referrer" content="no-referrer">
        <title>{{ $title ?? 'Kalcer.id - Hidden Gems Jaksel' }}</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans antialiased text-gray-900 bg-gradient-to-br from-purple-50 via-white to-pink-50 min-h-screen flex flex-col">
        
        <nav class="fixed top-0 w-full z-50 transition-all duration-300 backdrop-blur-md bg-white/90 border-b border-white/20 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 group" wire:navigate>
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="font-bold text-xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                                kalcer.id
                            </span>
                        </a>
                    </div>

                    <div class="hidden md:flex space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-purple-600 font-medium transition" wire:navigate>Home</a>
                        <a href="{{ route('maps') }}" class="text-gray-600 hover:text-purple-600 font-medium transition" wire:navigate>Maps</a>
                        <a href="{{ route('trending') }}" class="text-gray-600 hover:text-purple-600 font-medium transition" wire:navigate>Trending</a>
                        <a href="{{ route('about') }}" class="text-gray-600 hover:text-purple-600 font-medium transition" wire:navigate>About Us</a>
                    </div>

                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-gray-700 hover:text-purple-600" wire:navigate>
                                Hi, {{ substr(auth()->user()->name, 0, 10) }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium text-sm shadow-md hover:shadow-lg hover:from-purple-700 hover:to-pink-700 transform hover:-translate-y-0.5 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
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

        <footer class="bg-white border-t border-gray-100 py-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} Kalcer.ID. Kurasi Anak Jaksel.
            </div>
        </footer>

    </body>
</html>