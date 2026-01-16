@php
    $isBusiness = auth()->check() && (auth()->user()->role === 'business_owner' || auth()->user()->role === 'admin');
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp

<flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <div class="flex items-center gap-2 px-2 mb-4">
        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black font-syne shadow-lg">K.</div>
        <span class="font-syne font-bold text-lg tracking-tight dark:text-white">Kalcer.id</span>
    </div>

    <flux:navlist variant="outline">
        <flux:navlist.group heading="Menu">
            <flux:navlist.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>Home</flux:navlist.item>
            <flux:navlist.item icon="map" :href="route('maps')" :current="request()->routeIs('maps')" wire:navigate>Maps</flux:navlist.item>
            <flux:navlist.item icon="fire" :href="route('trending')" :current="request()->routeIs('trending')" wire:navigate>Trending</flux:navlist.item>
        </flux:navlist.group>

        @if($isBusiness)
        <flux:navlist.group heading="Bisnis" class="mt-4">
            @if($isAdmin)
                <flux:navlist.item icon="presentation-chart-line" :href="route('business.dashboard')" :current="request()->routeIs('business.dashboard')" wire:navigate>Admin Dashboard</flux:navlist.item>
            @else
                <flux:navlist.item icon="store-front" :href="route('business.index')" :current="request()->routeIs('business.index')" wire:navigate>Manage Business</flux:navlist.item>
            @endif
        </flux:navlist.group>
        @endif
    </flux:navlist>

    <flux:spacer />

    @guest
        <div class="grid gap-2">
            <flux:button href="{{ route('login') }}" variant="ghost" icon="arrow-right-end-on-rectangle">Log In</flux:button>
            <flux:button href="{{ route('register') }}" variant="primary">Sign Up</flux:button>
        </div>
    @endguest

</flux:sidebar>