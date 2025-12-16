<?php

use App\Models\HangoutPlace;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    use WithPagination;

    public $search = '';
    public $category = '';

    public function with(): array
    {
        $query = HangoutPlace::query()->where('is_verified', true);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        return [
            'places' => $query->orderBy('viral_score', 'desc')->paginate(6), // Tampilkan 6 di home
        ];
    }
}; ?>

<div>
    <section class="relative py-20 lg:py-32 px-4 text-center overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="relative max-w-4xl mx-auto space-y-6">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 leading-tight">
                Temukan Tempat <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Hangout Viral
                </span>
                di Jaksel
            </h1>
            <p class="text-xl text-gray-500 max-w-2xl mx-auto">
                Kurasi tempat nongkrong paling hits, estetik, dan nyaman buat WFC atau sekadar healing tipis-tipis.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
                <button class="px-8 py-4 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold shadow-lg shadow-purple-200 hover:shadow-xl hover:scale-105 transition transform duration-200">
                    Jelajahi Maps 🗺️
                </button>
                <button class="px-8 py-4 rounded-full bg-white border-2 border-purple-100 text-purple-600 font-bold hover:bg-purple-50 hover:border-purple-200 transition duration-200">
                    Lihat Trending 🔥
                </button>
            </div>
        </div>
    </section>

    <section class="border-y border-white/50 bg-white/50 backdrop-blur-sm py-8">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl font-extrabold text-gray-900">150+</div>
                <div class="text-sm text-gray-500 font-medium">Tempat Viral</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-gray-900">50k+</div>
                <div class="text-sm text-gray-500 font-medium">Review Jujur</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-gray-900">24/7</div>
                <div class="text-sm text-gray-500 font-medium">Real-time Data</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-gray-900">#1</div>
                <div class="text-sm text-gray-500 font-medium">Platform Jaksel</div>
            </div>
        </div>
    </section>

    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <h2 class="text-2xl font-bold text-gray-900">Rekomendasi Minggu Ini ✨</h2>
            
            <div class="flex gap-2 overflow-x-auto pb-2 w-full md:w-auto">
                <button wire:click="$set('category', '')" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition {{ $category == '' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">All Spots</button>
                <button wire:click="$set('category', 'Coffee Shop')" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition {{ $category == 'Coffee Shop' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">☕ Coffee</button>
                <button wire:click="$set('category', 'Creative Hub')" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition {{ $category == 'Creative Hub' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">🎨 Artsy</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($places as $place)
                <div class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col h-full overflow-hidden">
                    
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="object-cover w-full h-full group-hover:scale-110 transition duration-700">
                        
                        <div class="absolute top-4 left-4">
                             @if($place->viral_score > 90)
                                <span class="bg-white/90 backdrop-blur text-purple-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                                    #1 TRENDING
                                </span>
                             @endif
                        </div>

                        <div class="absolute top-4 right-4 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md font-bold text-sm text-gray-900 border-2 border-purple-100">
                            {{ $place->viral_score }}
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <div class="mb-2 flex justify-between items-start">
                            <div>
                                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded uppercase tracking-wide">
                                    {{ $place->category }}
                                </span>
                                <h3 class="mt-2 text-xl font-bold text-gray-900 group-hover:text-purple-600 transition">
                                    {{ $place->name }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-1 text-yellow-500 font-bold text-sm">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span>4.8</span>
                            </div>
                        </div>

                        <p class="text-gray-500 text-sm line-clamp-2 mb-4">
                            {{ $place->description }}
                        </p>

                        <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-3 text-xs text-gray-400 font-medium">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.069-3.204 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.069-4.849 0-3.204.013-3.583.069-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    12k
                                </span>
                            </div>

                            <a href="{{ route('place.show', $place) }}" class="text-sm font-bold text-purple-600 hover:text-pink-600 transition" wire:navigate>
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-500">Belum ada tempat yang cocok dengan filter kamu.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-12 flex justify-center">
            {{ $places->links() }}
        </div>
    </section>
</div>