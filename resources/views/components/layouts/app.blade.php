<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Kalcer.id' }}</title>
        
        <script src='https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.css' rel='stylesheet' />
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            h1, h2, h3, .font-syne { font-family: 'Syne', sans-serif; }
            
            /* FIX MAPBOX HEIGHT: Pastikan map punya tinggi jika parentnya flex */
            .mapboxgl-map { min-height: 100%; height: 100%; width: 100%; }
        </style>
    </head>
    
    <body class="min-h-screen bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 flex flex-col">
        
        @include('components.layouts.app.header')

        <main class="flex-grow relative w-full">
            <div class="fixed inset-0 z-[-1] opacity-20 pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>
            
            {{ $slot }}
        </main>

        <footer class="text-center text-xs text-zinc-500 py-6 border-t border-zinc-200 dark:border-zinc-800">
            <p>&copy; {{ date('Y') }} Kalcer.id. All rights reserved.</p>
        </footer>

        <script>
            // Dark Mode Init
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
            
            function toggleTheme() {
                document.documentElement.classList.toggle('dark');
                localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            }
        </script>
    </body>
</html>