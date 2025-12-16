<?php

use App\Models\HangoutPlace;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    public $search = '';
    public $category = '';
    
    // Kita gunakan computed property agar data selalu fresh saat filter berubah
    public function with(): array
    {
        $query = HangoutPlace::query()->where('is_verified', true);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        // Ambil semua data (tanpa pagination) agar semua marker muncul di peta
        return [
            'places' => $query->get(), 
        ];
    }
}; ?>

<div class="flex flex-col lg:flex-row h-[calc(100vh-64px)] overflow-hidden bg-white">
    
    <div class="w-full lg:w-1/3 flex flex-col border-r border-gray-200 bg-white h-1/2 lg:h-full z-10 shadow-xl lg:shadow-none">
        
        <div class="p-5 border-b border-gray-100 bg-white z-20">
            <h1 class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-4">
                Jelajahi Maps 🗺️
            </h1>
            
            <div class="relative mb-4">
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    placeholder="Cari tempat viral...">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                <button wire:click="$set('category', '')" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-bold transition {{ $category == '' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All</button>
                <button wire:click="$set('category', 'Coffee Shop')" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-bold transition {{ $category == 'Coffee Shop' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">☕ Coffee</button>
                <button wire:click="$set('category', 'Creative Hub')" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-bold transition {{ $category == 'Creative Hub' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">🎨 Artsy</button>
                <button wire:click="$set('category', 'Public Space')" class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-bold transition {{ $category == 'Public Space' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">🌳 Nature</button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" id="places-list">
            @forelse($places as $place)
                <div 
                    wire:key="{{ $place->id }}"
                    x-on:click="$dispatch('focus-map', { lat: {{ $place->latitude }}, lng: {{ $place->longitude }}, id: {{ $place->id }} })"
                    class="group bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md cursor-pointer transition-all duration-200 hover:border-purple-400"
                    :class="selectedId === {{ $place->id }} ? 'ring-2 ring-purple-500 border-transparent' : ''"
                >
                    <div class="flex gap-4">
                        <img src="{{ $place->image_url }}" alt="" class="w-20 h-20 rounded-lg object-cover bg-gray-200">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-gray-900 truncate">{{ $place->name }}</h3>
                                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">{{ $place->viral_score }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $place->address }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[10px] font-bold uppercase tracking-wide 
                                    {{ $place->crowd_level == 'penuh' ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50' }} 
                                    px-2 py-0.5 rounded-full">
                                    {{ $place->crowd_level }}
                                </span>
                                <span class="text-[10px] text-gray-400">{{ $place->category }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400">
                    Tidak ada tempat ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <div class="w-full lg:w-2/3 h-1/2 lg:h-full relative bg-gray-200"
         x-data="mapComponent(@js($places))" 
         x-init="initMap()"
    >
        <div id="leaflet-map" class="w-full h-full z-0"></div>

        <div x-show="selectedPlace" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute bottom-6 left-6 right-6 lg:left-auto lg:right-6 lg:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 z-[500]"
             style="display: none;">
            
            <template x-if="selectedPlace">
                <div>
                    <div class="relative h-32 rounded-xl overflow-hidden mb-3">
                        <img :src="selectedPlace.image_url" class="w-full h-full object-cover">
                        <button @click="selectedPlace = null; selectedId = null" class="absolute top-2 right-2 bg-black/50 text-white p-1 rounded-full hover:bg-black">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900" x-text="selectedPlace.name"></h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mt-1" x-text="selectedPlace.description"></p>
                    
                    <a :href="'/place/' + selectedPlace.id" class="mt-4 flex items-center justify-center w-full py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg text-sm shadow-md hover:shadow-lg transition">
                        Lihat Detail Lengkap →
                    </a>
                </div>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mapComponent', (placesData) => ({
                map: null,
                markers: [],
                places: placesData,
                selectedId: null,
                selectedPlace: null,

                initMap() {
                    // 1. Inisialisasi Peta
                    this.map = L.map('leaflet-map', { zoomControl: false }).setView([-6.244229, 106.802870], 13);
                    
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; OpenStreetMap &copy; CARTO',
                        maxZoom: 20
                    }).addTo(this.map);

                    L.control.zoom({ position: 'topright' }).addTo(this.map);

                    // 2. Render Marker Awal
                    this.updateMarkers(this.places);

                    // 3. Listener: Saat user klik item di sidebar
                    window.addEventListener('focus-map', (e) => {
                        const { lat, lng, id } = e.detail;
                        this.map.flyTo([lat, lng], 16, { duration: 1.5 });
                        this.selectPlace(id);
                    });

                    // 4. Listener: Saat Livewire mengupdate data (filter)
                    this.$watch('places', (newPlaces) => {
                        this.updateMarkers(newPlaces);
                    });
                },

                updateMarkers(newPlaces) {
                    // Hapus marker lama
                    this.markers.forEach(m => this.map.removeLayer(m));
                    this.markers = [];

                    // Tambah marker baru
                    newPlaces.forEach(place => {
                        const marker = L.marker([place.latitude, place.longitude])
                            .addTo(this.map)
                            .on('click', () => {
                                this.selectPlace(place.id);
                                this.map.flyTo([place.latitude, place.longitude], 16, { duration: 1 });
                            });
                        
                        this.markers.push(marker);
                    });
                },

                selectPlace(id) {
                    this.selectedId = id;
                    // Cari data lengkap dari array places
                    // Kita ambil dari data awal PHP yang di-pass ke Alpine, atau fetch ulang jika perlu
                    // Untuk simpelnya, kita cari di data yang ada sekarang
                    this.selectedPlace = this.places.find(p => p.id === id);
                }
            }))
        })
    </script>
</div>