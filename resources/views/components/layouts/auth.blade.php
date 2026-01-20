<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Kalcer.id Auth' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- BACKGROUND --}}
<body class="font-sans antialiased bg-[#0a031b] text-white min-h-screen flex flex-col justify-center items-center relative overflow-hidden selection:bg-red-500 selection:text-white">

    {{-- BACKGROUND AMBIENCE --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        
        {{-- Floating Orbs --}}
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-purple-600/30 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-pink-500/20 rounded-full blur-[100px] animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    {{-- LOGO --}}
    <div class="relative z-10 mb-8 text-center group">
        <a href="{{ route('home') }}" wire:navigate class="inline-block">
            <h1 class="text-4xl font-black font-syne tracking-tighter flex items-center gap-2 drop-shadow-[0_0_15px_rgba(168,85,247,0.5)]">
                <i class="fa-solid fa-layer-group text-purple-500 group-hover:rotate-12 transition-transform duration-300"></i>
                <span class="text-white">Kalcer<span class="text-pink-500">.id</span></span>
            </h1>
        </a>
    </div>

    {{-- CARD CONTAINER --}}
    <div class="relative z-10 w-full sm:max-w-md px-8 py-10 bg-black/40 backdrop-blur-xl border border-white/10 rounded-[2rem] shadow-2xl shadow-black/50 overflow-hidden">
        {{-- Top Gradient Line --}}
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-600 via-pink-500 to-purple-600"></div>
        
        {{ $slot }}
    </div>

    {{-- COPYRIGHT --}}
    <div class="relative z-10 mt-8 text-zinc-500 text-xs font-bold tracking-widest uppercase">
        Jakarta Selatan Pride © {{ date('Y') }}
    </div>

</body>
</html>