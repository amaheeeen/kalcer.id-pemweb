<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

new 
#[Layout('components.layouts.app')]
#[Title('My Wishlist')]
class extends Component {
    use WithPagination;

    public function with()
    {
        // Redirect jika belum login
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        return [
            // Ambil data bookmarks milik user yang sedang login
            'places' => Auth::user()->bookmarks()->orderByPivot('created_at', 'desc')->paginate(12)
        ];
    }

    // Fungsi Hapus Bookmark langsung dari halaman list
    public function removeBookmark($id)
    {
        Auth::user()->bookmarks()->detach($id);
        // Tidak perlu refresh, Livewire otomatis re-render karena data 'places' berubah
    }
}; ?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-7xl mx-auto mb-10">
        <h1 class="text-3xl md:text-4xl font-black font-syne text-zinc-900 dark:text-white mb-2 flex items-center gap-3">
            <i class="fa-solid fa-heart text-pink-500"></i> My Wishlist
        </h1>
        <p class="text-zinc-500 dark:text-zinc-400 text-lg">
            Koleksi tempat nongkrong favorit yang udah kamu simpan. Kapan mau gas?
        </p>
    </div>

    <div class="max-w-7xl mx-auto">
        
        @if($places->isEmpty())
            <div class="text-center py-20 bg-white dark:bg-zinc-800/50 rounded-3xl border border-dashed border-zinc-300 dark:border-zinc-700">
                <div class="text-6xl mb-4 opacity-50">💔</div>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Wishlist Masih Kosong</h3>
                <p class="text-zinc-500 mb-6">Kamu belum menyimpan tempat apapun.</p>
                <a href="{{ route('explore') }}" wire:navigate class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 transition shadow-lg shadow-indigo-500/30">
                    Jelajahi Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($places as $place)
                    <div class="group relative bg-white dark:bg-zinc-900 rounded-3xl overflow-hidden border border-zinc-200 dark:border-zinc-800 hover:border-pink-500/50 transition duration-300">
                        
                        <button wire:click="removeBookmark({{ $place->id }})" wire:confirm="Yakin mau hapus dari wishlist?" class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-black/50 backdrop-blur text-white flex items-center justify-center hover:bg-red-500 transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>

                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $place->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900/80 to-transparent"></div>
                            <div class="absolute bottom-3 left-4 text-white">
                                <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded backdrop-blur-md border border-white/10 mb-1 inline-block">{{ $place->category }}</span>
                                <h3 class="font-bold font-syne text-lg leading-tight">{{ $place->name }}</h3>
                            </div>
                        </div>

                        <div class="p-4 flex items-center justify-between">
                            <div class="text-xs text-zinc-500 flex flex-col gap-1">
                                <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ Str::limit($place->address, 25) }}</span>
                                <span class="flex items-center gap-1"><i class="fa-solid fa-star text-yellow-500"></i> {{ $place->avg_rating }} Rating</span>
                            </div>
                            
                            <a href="{{ route('place.show', $place->id) }}" wire:navigate class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition">
                                Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $places->links() }}
            </div>
        @endif
    </div>
</div>