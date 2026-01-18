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

    // State untuk Filter & Search
    public $search = '';
    public $category = 'all';
    public $sort = 'newest';

    // [BARU] State untuk Filter Fitur No. 3
    public $price = []; // Array karena checkbox (bisa pilih $ dan $$ sekaligus)
    public $personality = 'all'; // String karena radio button (pilih satu)

    // Reset pagination saat filter berubah agar tidak error
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }
    public function updatedPrice() { $this->resetPage(); }
    public function updatedPersonality() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'category', 'price', 'personality', 'sort']);
    }

    public function with()
    {
        $query = HangoutPlace::query();

        // 1. Filter Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Category
        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        // [BARU] 3. Filter Harga (Checkboxes)
        if (!empty($this->price)) {
            $query->whereIn('price_range', $this->price);
        }

        // [BARU] 4. Filter Psikologi (Radio)
        if ($this->personality !== 'all') {
            $query->where('personality_type', $this->personality);
        }

        // 5. Sorting
        switch ($this->sort) {
            case 'viral': $query->orderByDesc('viral_score'); break;
            case 'popular': $query->orderByDesc('profile_views'); break;
            case 'oldest': $query->orderBy('created_at', 'asc'); break;
            default: $query->orderByDesc('created_at');
        }

        return [
            'places' => $query->paginate(9), // Ubah jadi 9 agar grid 3x3 rapi
            'categories' => HangoutPlace::select('category')->distinct()->pluck('category'),
            'trendingPlaces' => HangoutPlace::orderByDesc('viral_score')->take(7)->get(),
        ];
    }
}; ?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 transition-colors duration-300 overflow-x-hidden">
    
    {{-- HERO SECTION --}}
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

    {{-- TRENDING SLIDER --}}
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

    {{-- MAIN CONTENT AREA (SEARCH + FILTER SIDEBAR + GRID) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        <div class="mb-8">
            <div class="relative w-full group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-zinc-400 group-focus-within:text-indigo-500 transition"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="block w-full pl-11 pr-4 py-4 border-none rounded-2xl bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-500 shadow-lg shadow-zinc-200/50 dark:shadow-none focus:ring-2 focus:ring-indigo-500 text-lg font-medium transition" 
                    placeholder="Cari nama tempat, jalan, atau daerah...">
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- SIDEBAR FILTER (FITUR NO. 3) --}}
            <div class="w-full lg:w-72 flex-shrink-0 space-y-6">
                
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-[2rem] shadow-xl border border-zinc-100 dark:border-zinc-800 sticky top-24">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-lg text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-filter text-indigo-500"></i> Smart Filter
                        </h3>
                        <button wire:click="resetFilters" class="text-xs font-bold text-red-500 hover:text-red-600 hover:underline transition">
                            Reset
                        </button>
                    </div>

                    {{-- 1. Filter Psikologi/Personality --}}
                    <div class="mb-8">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3 block">Vibes / Personality</label>
                        <div class="space-y-3">
                            @foreach(['all' => 'Semua', 'introvert' => 'Introvert (Tenang)', 'ambivert' => 'Ambivert (Santai)', 'extrovert' => 'Extrovert (Ramai)'] as $val => $label)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="radio" wire:model.live="personality" value="{{ $val }}" class="peer sr-only">
                                        <div class="w-5 h-5 border-2 border-zinc-300 dark:border-zinc-600 rounded-full peer-checked:border-indigo-500 peer-checked:bg-indigo-500 transition flex items-center justify-center">
                                            <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition"></div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-zinc-600 dark:text-zinc-300 group-hover:text-indigo-500 transition">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. Filter Harga --}}
                    <div class="mb-8">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3 block">Budget Range</label>
                        <div class="flex gap-2">
                            @foreach([1 => '$', 2 => '$$', 3 => '$$$'] as $val => $label)
                                <label class="cursor-pointer flex-1">
                                    <input type="checkbox" wire:model.live="price" value="{{ $val }}" class="sr-only peer">
                                    <div class="py-2 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 font-bold text-sm text-zinc-400 text-center peer-checked:border-indigo-500 peer-checked:text-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 transition hover:border-indigo-300">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-zinc-400 mt-2 text-center">* $ < 50k | $$ < 100k | $$$ > 100k</p>
                    </div>

                    {{-- 3. Filter Kategori --}}
                    <div class="mb-8">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3 block">Kategori</label>
                        <select wire:model.live="category" class="w-full bg-zinc-50 dark:bg-zinc-800 border-none rounded-xl text-sm font-bold text-zinc-700 dark:text-zinc-300 focus:ring-2 focus:ring-indigo-500 p-3 cursor-pointer">
                            <option value="all">📁 Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Sorting --}}
                    <div>
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3 block">Urutkan</label>
                        <select wire:model.live="sort" class="w-full bg-zinc-50 dark:bg-zinc-800 border-none rounded-xl text-sm font-bold text-zinc-700 dark:text-zinc-300 focus:ring-2 focus:ring-indigo-500 p-3 cursor-pointer">
                            <option value="newest">✨ Terbaru</option>
                            <option value="viral">🔥 Paling Viral</option>
                            <option value="popular">👀 Paling Banyak Dilihat</option>
                            <option value="oldest">📅 Terlama</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- GRID CONTENT --}}
            <div class="flex-1">
                
                {{-- Loading State --}}
                <div wire:loading.flex class="w-full py-20 items-center justify-center">
                    <div class="flex flex-col items-center gap-4">
                        <i class="fa-solid fa-circle-notch fa-spin text-3xl text-indigo-500"></i>
                        <span class="text-zinc-500 dark:text-zinc-400 text-sm font-bold animate-pulse">Memfilter hasil...</span>
                    </div>
                </div>

                @if($places->isEmpty())
                    <div wire:loading.remove class="flex flex-col items-center justify-center py-20 bg-zinc-100 dark:bg-zinc-900/50 rounded-[2rem] border border-zinc-200 dark:border-white/5 text-center">
                        <div class="text-6xl mb-4 grayscale opacity-50">🍃</div>
                        <h3 class="text-2xl font-black text-zinc-900 dark:text-white mb-2 font-syne">Yah, Kosong!</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm max-w-xs mx-auto mb-6">
                            Tidak ada tempat yang cocok dengan filter kamu. Coba kurangi filternya.
                        </p>
                        <button wire:click="resetFilters" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition shadow-lg">
                            Reset Semua Filter
                        </button>
                    </div>
                @else
                    <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($places as $place)
                            <div class="group relative bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-[2rem] overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                                
                                {{-- Image Wrapper --}}
                                <div class="relative h-60 overflow-hidden">
                                    <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/20 to-transparent opacity-60"></div>
                                    
                                    {{-- Badge Kategori --}}
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-[10px] font-black uppercase tracking-widest rounded-full text-zinc-900 shadow-sm">
                                            {{ $place->category }}
                                        </span>
                                    </div>

                                    {{-- Badge Harga --}}
                                    <div class="absolute bottom-3 right-3">
                                        <div class="bg-black/60 backdrop-blur-md px-3 py-1 rounded-lg text-white font-bold text-xs border border-white/10">
                                            {{ str_repeat('$', $place->price_range) }}
                                        </div>
                                    </div>

                                    {{-- Badge Personality --}}
                                    <div class="absolute top-3 right-3">
                                        @php
                                            $badgeColor = match($place->personality_type) {
                                                'introvert' => 'bg-purple-500',
                                                'extrovert' => 'bg-orange-500',
                                                default => 'bg-blue-500',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 {{ $badgeColor }} text-white text-[10px] font-bold uppercase rounded-md shadow-lg">
                                            {{ $place->personality_type }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="p-5 flex flex-col flex-1">
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex items-center gap-1 text-yellow-500 text-xs font-bold">
                                            <i class="fa-solid fa-star"></i> {{ $place->avg_rating ?? '4.5' }}
                                        </div>
                                        @if($place->crowd_status)
                                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500">
                                                {{ $place->crowd_status }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2 line-clamp-1 group-hover:text-indigo-600 transition">
                                        <a href="{{ route('place.show', $place->id) }}" wire:navigate>
                                            {{ $place->name }}
                                        </a>
                                    </h3>
                                    
                                    <p class="text-zinc-500 dark:text-zinc-400 text-xs line-clamp-2 mb-4 flex-1 leading-relaxed">
                                        {{ $place->description }}
                                    </p>

                                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between mt-auto">
                                        <div class="text-xs text-zinc-400 font-medium truncate max-w-[140px]">
                                            <i class="fa-solid fa-location-dot mr-1"></i> {{ Str::limit($place->address, 20) }}
                                        </div>
                                        <a href="{{ route('place.show', $place->id) }}" wire:navigate class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-900 dark:text-white hover:bg-indigo-600 hover:text-white transition group-hover:scale-110">
                                            <i class="fa-solid fa-arrow-right -rotate-45"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $places->links(data: ['scrollTo' => false]) }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>