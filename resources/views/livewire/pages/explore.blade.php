<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\HangoutPlace;

new 
#[Layout('components.layouts.app')]
#[Title('Explore Hidden Gems')]
class extends Component {
    use WithPagination;

    // State untuk Filter
    public $search = '';
    public $category = 'all';
    public $sort = 'newest';

    // Reset pagination saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }

    public function with()
    {
        $query = HangoutPlace::query();

        // 1. Filter Search
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
        }

        // 2. Filter Category
        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        // 3. Sorting berdasarkan pilihan user
        switch ($this->sort) {
            case 'viral': $query->orderByDesc('viral_score'); break;
            case 'popular': $query->orderByDesc('profile_views'); break;
            case 'oldest': $query->orderBy('created_at', 'asc'); break;
            default: $query->orderByDesc('created_at');
        }

        return [
            // Data utama untuk Grid (Paginated)
            'places' => $query->paginate(12),
            // Data untuk Dropdown Kategori
            'categories' => HangoutPlace::select('category')->distinct()->pluck('category'),
            // DATA BARU: Top 7 Trending untuk Hero Slider
            'trendingPlaces' => HangoutPlace::orderByDesc('viral_score')->take(7)->get(),
        ];
    }
}; ?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 transition-colors duration-300 overflow-x-hidden">
    
    <div class="relative pt-12 pb-6 text-center px-4">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-indigo-500/30 dark:bg-indigo-500/20 blur-[120px] rounded-full pointer-events-none z-0"></div>

        <div class="relative z-10">
            <span class="inline-flex items-center gap-1 py-1 px-3 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-300 text-xs font-bold tracking-wider uppercase mb-3 ring-1 ring-indigo-500/30">
                <i class="fa-solid fa-compass animate-pulse"></i> Discovery Hub
            </span>
            <h1 class="text-4xl md:text-6xl font-black font-syne text-zinc-900 dark:text-white mb-4 tracking-tight leading-tight">
                Find Your Next <br class="md:hidden"> 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 animate-gradient-x">Vibe.</span>
            </h1>
            <p class="text-zinc-600 dark:text-zinc-400 max-w-xl mx-auto text-lg font-medium">
                Katalog lengkap spot nongkrong di Jakarta Selatan. 
            </p>
        </div>
    </div>

    <div class="relative max-w-[95rem] mx-auto mb-12">
        <div class="px-4 sm:px-6 lg:px-8 mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-fire text-orange-500"></i> Sedang Hype
            </h2>
            <span class="text-xs text-zinc-500 dark:text-zinc-400">Geser untuk lihat →</span>
        </div>

        <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 px-4 sm:px-6 lg:px-8 no-scrollbar pb-4" style="scroll-padding-left: 1rem; scroll-padding-right: 1rem;">
            @foreach($trendingPlaces as $trend)
                <a href="{{ route('place.show', $trend->id) }}" wire:navigate class="snap-center shrink-0 relative w-[280px] md:w-[320px] h-[400px] md:h-[450px] rounded-[2rem] overflow-hidden group ring-1 ring-zinc-900/5 dark:ring-white/10 shadow-xl dark:shadow-none transition-all duration-500 hover:scale-[1.02]">
                    <img src="{{ $trend->image_url }}" alt="{{ $trend->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-90"></div>
                    
                    <div class="absolute top-4 right-4">
                        <div class="flex items-center gap-1 px-3 py-1.5 bg-orange-500 text-white text-xs font-bold uppercase tracking-wider rounded-full shadow-lg shadow-orange-500/20 backdrop-blur-md">
                           🔥 {{ $trend->viral_score }} Viral
                        </div>
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 transition duration-500">
                        <span class="text-indigo-300 text-xs font-bold uppercase tracking-wider mb-2 block">{{ $trend->category }}</span>
                        <h3 class="text-2xl md:text-3xl font-black text-white font-syne leading-none mb-3 drop-shadow-lg">{{ $trend->name }}</h3>
                        <div class="flex items-center justify-between text-zinc-300 text-sm font-medium opacity-0 group-hover:opacity-100 transition duration-500 delay-100">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-dot"></i> {{ Str::limit($trend->address, 20) }}</span>
                            <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center"><i class="fa-solid fa-arrow-right -rotate-45"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>


    <div class="max-w-7xl mx-auto mb-8 sticky top-20 z-40 px-4 sm:px-6 lg:px-8">
        <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl border border-zinc-200/50 dark:border-white/10 rounded-2xl p-3 shadow-lg shadow-zinc-200/20 dark:shadow-black/20 flex flex-col md:flex-row gap-3 items-center justify-between transition-all duration-300">
            
            <div class="relative w-full md:flex-1 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-zinc-400 group-focus-within:text-indigo-500 transition"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="block w-full pl-11 pr-4 py-3 border-none rounded-xl bg-zinc-100/80 dark:bg-black/30 text-zinc-900 dark:text-white placeholder-zinc-500 focus:ring-2 focus:ring-indigo-500 font-medium transition" 
                    placeholder="Cari nama tempat, jalan, atau daerah...">
            </div>

            <div class="flex gap-2 overflow-x-auto w-full md:w-auto no-scrollbar">
                <div class="relative shrink-0">
                    <select wire:model.live="category" class="appearance-none bg-zinc-100/80 dark:bg-black/30 border-none text-zinc-700 dark:text-zinc-300 font-bold text-sm rounded-xl focus:ring-indigo-500 py-3 pl-4 pr-10 cursor-pointer hover:bg-zinc-200/80 dark:hover:bg-zinc-800/50 transition min-w-[140px]">
                        <option value="all">📁 All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 text-xs pointer-events-none"></i>
                </div>

                <div class="relative shrink-0">
                    <select wire:model.live="sort" class="appearance-none bg-zinc-100/80 dark:bg-black/30 border-none text-zinc-700 dark:text-zinc-300 font-bold text-sm rounded-xl focus:ring-indigo-500 py-3 pl-4 pr-10 cursor-pointer hover:bg-zinc-200/80 dark:hover:bg-zinc-800/50 transition min-w-[140px]">
                        <option value="newest">✨ Newest First</option>
                        <option value="viral">🔥 Top Viral</option>
                        <option value="popular">👀 Most Viewed</option>
                        <option value="oldest">📅 Oldest First</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 text-xs pointer-events-none"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        <div wire:loading.flex class="w-full py-20 items-center justify-center">
            <div class="flex flex-col items-center gap-4">
                <div class="relative flex h-12 w-12">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-12 w-12 bg-indigo-500"></span>
                </div>
                <span class="text-zinc-500 dark:text-zinc-400 text-sm font-bold animate-pulse">Sedang mencari hidden gems...</span>
            </div>
        </div>

        @if($places->isEmpty())
            <div wire:loading.remove class="text-center py-20 bg-zinc-100 dark:bg-zinc-900/50 rounded-3xl border border-zinc-200 dark:border-white/5">
                <div class="text-7xl mb-6 filter grayscale opacity-50">🏜️</div>
                <h3 class="text-2xl font-black text-zinc-900 dark:text-white mb-3 font-syne">Oops, Belum Ketemu!</h3>
                <p class="text-zinc-500 dark:text-zinc-400 max-w-md mx-auto mb-6">
                    Coba gunakan kata kunci yang berbeda atau reset filter kategori Anda.
                </p>
                <button wire:click="$set('search', '')" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-1">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Reset Pencarian
                </button>
            </div>
        @else
            <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($places as $place)
                    <div class="group relative bg-white dark:bg-zinc-900/80 backdrop-blur-sm border border-zinc-200/50 dark:border-white/10 rounded-[2rem] overflow-hidden hover:border-indigo-500/50 dark:hover:border-indigo-400/50 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500">
                        
                        <div class="relative h-72 overflow-hidden">
                            <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/40 to-transparent opacity-80"></div>
                            
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1.5 bg-zinc-900/50 backdrop-blur-md border border-white/20 rounded-full text-[10px] font-bold text-white uppercase tracking-wider">
                                    {{ $place->category }}
                                </span>
                            </div>

                            <div class="absolute top-4 right-4">
                                <div class="flex items-center justify-center w-11 h-11 rounded-full bg-zinc-900/60 backdrop-blur-md border-2 {{ $place->viral_score > 90 ? 'border-pink-500 text-pink-400' : ($place->viral_score > 75 ? 'border-indigo-500 text-indigo-400' : 'border-zinc-500 text-zinc-400') }} text-xs font-black shadow-lg">
                                    {{ $place->viral_score }}
                                </div>
                            </div>
                        </div>

                        <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 transition duration-500 ease-out">
                            <h3 class="text-2xl font-black text-white font-syne leading-tight mb-2 drop-shadow-sm truncate">{{ $place->name }}</h3>
                            <p class="text-zinc-300 text-sm line-clamp-1 mb-4 flex items-center gap-2 opacity-80">
                                <i class="fa-solid fa-location-dot text-indigo-400"></i>
                                {{ Str::limit($place->address, 35) }}
                            </p>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-white/10 opacity-0 group-hover:opacity-100 transition duration-500 delay-75">
                                <div class="flex gap-4 text-xs text-zinc-300 font-bold uppercase tracking-wider">
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-eye text-indigo-400"></i> {{ number_format($place->profile_views) }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-signal text-{{ $place->crowd_level == 'ramai' ? 'red' : 'green' }}-400"></i> {{ $place->crowd_level }}</span>
                                </div>
                                <a href="{{ route('place.show', $place->id) }}" wire:navigate class="w-10 h-10 rounded-full bg-indigo-600 hover:bg-indigo-500 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 transition transform hover:rotate-12 hover:scale-110">
                                    <i class="fa-solid fa-arrow-right-long"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16">
                {{ $places->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>
</div>