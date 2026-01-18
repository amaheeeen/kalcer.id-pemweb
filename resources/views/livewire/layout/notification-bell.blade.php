<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public function getNotificationsProperty()
    {
        return Auth::check() ? Auth::user()->unreadNotifications : collect([]);
    }

    public function markAsRead($id)
    {
        Auth::user()->notifications()->where('id', $id)->first()?->markAsRead();
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }
}; ?>

<div class="relative ml-2" x-data="{ open: false }">
    <button @click="open = !open" @click.outside="open = false" class="relative w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center hover:bg-zinc-200 dark:hover:bg-zinc-700 transition active:scale-95">
        <i class="fa-regular fa-bell text-zinc-600 dark:text-zinc-300"></i>
        
        @if($this->notifications->count() > 0)
            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 border-2 border-white dark:border-zinc-900 rounded-full animate-pulse"></span>
        @endif
    </button>

    <div x-show="open" 
         x-transition.opacity
         x-cloak
         class="absolute right-0 mt-4 w-80 bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden z-50">
        
        <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center bg-zinc-50/50 dark:bg-zinc-800/50">
            <h3 class="font-bold text-sm text-zinc-900 dark:text-white">Notifikasi</h3>
            @if($this->notifications->count() > 0)
                <button wire:click="markAllRead" class="text-[10px] font-bold text-indigo-600 hover:underline">
                    Baca semua
                </button>
            @endif
        </div>

        <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
            @forelse($this->notifications as $notif)
                <div wire:key="{{ $notif->id }}" class="p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition border-b border-zinc-100 dark:border-zinc-800/50 flex gap-3">
                    <img src="{{ $notif->data['image'] ?? 'https://placehold.co/100' }}" class="w-10 h-10 rounded-lg object-cover shrink-0 bg-zinc-200">
                    
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">
                            {{ $notif->data['title'] }}
                        </p>
                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400 leading-tight mb-1 line-clamp-2">
                            {{ $notif->data['message'] }}
                        </p>
                        <p class="text-[9px] text-zinc-400">
                            {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    <div class="flex flex-col justify-between items-end">
                        <button wire:click="markAsRead('{{ $notif->id }}')" class="text-zinc-300 hover:text-indigo-500 transition" title="Tandai dibaca">
                            <i class="fa-solid fa-check text-xs"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <i class="fa-regular fa-bell-slash text-xl text-zinc-300 mb-2"></i>
                    <p class="text-xs text-zinc-500">Belum ada notifikasi.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>