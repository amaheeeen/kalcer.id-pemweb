<?php

use App\Models\HangoutPlace;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
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
            'places' => $query->get(), 
        ];
    }
}; ?>

<div class="flex flex-col lg:flex-row h-[calc(100vh-130px)] rounded-3xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-2xl relative"
     x-data="mapboxComponent(@js($places))" 
     x-init="initMap()"
>
    <div class="w-full lg:w-1/3 flex flex-col border-r border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 h-1/2 lg:h-full z-10 relative">
        
        <div class="p-6 border-b border-gray-100 dark:border-white/5 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md z-20 sticky top-0">
            <h1 class="text-3xl font-black brand-font mb-1 text-gray-900 dark:text-white">
                Explore 🗺️
            </h1>
            <p class="text-sm text-gray-500 mb-4">Temukan hidden gems di sekitarmu.</p>
            
            <div class="relative mb-4 group">
                <input wire:model.live.debounce.300ms="search" type="text" 
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none text-sm font-bold"
                    placeholder="Cari tempat nongkrong...">
                <span class="absolute left-4 top-3.5 text-gray-400 text-lg">🔍</span>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                <button wire:click="$set('category', '')" class="whitespace-nowrap px-4 py-2 rounded-xl text-xs font-bold transition {{ $category == '' ? 'bg-black dark:bg-white text-white dark:text-black shadow-lg' : 'bg-gray-100 dark:bg-white/5 text-gray-500 hover:bg-gray-200 dark:hover:bg-white/10' }}">All</button>
                <button wire:click="$set('category', 'Coffee Shop')" class="whitespace-nowrap px-4 py-2 rounded-xl text-xs font-bold transition {{ $category == 'Coffee Shop' ? 'bg-black dark:bg-white text-white dark:text-black shadow-lg' : 'bg-gray-100 dark:bg-white/5 text-gray-500 hover:bg-gray-200 dark:hover:bg-white/10' }}">☕ Coffee</button>
                <button wire:click="$set('category', 'Creative Hub')" class="whitespace-nowrap px-4 py-2 rounded-xl text-xs font-bold transition {{ $category == 'Creative Hub' ? 'bg-black dark:bg-white text-white dark:text-black shadow-lg' : 'bg-gray-100 dark:bg-white/5 text-gray-500 hover:bg-gray-200 dark:hover:bg-white/10' }}">🎨 Artsy</button>
                <button wire:click="$set('category', 'Public Space')" class="whitespace-nowrap px-4 py-2 rounded-xl text-xs font-bold transition {{ $category == 'Public Space' ? 'bg-black dark:bg-white text-white dark:text-black shadow-lg' : 'bg-gray-100 dark:bg-white/5 text-gray-500 hover:bg-gray-200 dark:hover:bg-white/10' }}">🌳 Nature</button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-black/20">
            @forelse($places as $place)
                <div 
                    wire:key="{{ $place->id }}"
                    @click="flyToLocation({{ $place->latitude }}, {{ $place->longitude }}, {{ $place->id }})"
                    class="group bg-white dark:bg-white/5 p-3 rounded-2xl border border-gray-100 dark:border-white/5 hover:border-indigo-500 dark:hover:border-indigo-500 cursor-pointer transition-all duration-200 hover:shadow-xl hover:scale-[1.02]"
                    :class="selectedId === {{ $place->id }} ? 'ring-2 ring-indigo-500 border-transparent' : ''"
                >
                    <div class="flex gap-4 items-center">
                        <img src="{{ $place->image_url }}" class="w-16 h-16 rounded-xl object-cover bg-gray-200">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate text-sm">{{ $place->name }}</h3>
                                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 dark:text-indigo-300 px-2 py-0.5 rounded-full">🔥 {{ $place->viral_score }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">{{ $place->address }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full
                                    {{ $place->crowd_level == 'penuh' ? 'text-red-600 bg-red-50 dark:bg-red-900/20' : 'text-green-600 bg-green-50 dark:bg-green-900/20' }}">
                                    {{ $place->crowd_level }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-4xl mb-2">🏜️</p>
                    <p class="text-gray-400 font-bold">Belum ada tempat di sini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="w-full lg:w-2/3 h-1/2 lg:h-full relative bg-gray-200 dark:bg-slate-800">
        <div id="map" class="w-full h-full z-0"></div>

        <div class="absolute top-4 right-4 flex flex-col gap-2 z-10">
            <button @click="toggleLayer('traffic')" 
                    class="p-3 rounded-xl backdrop-blur-md border transition-all shadow-lg font-bold text-xs flex items-center gap-2"
                    :class="activeLayer === 'traffic' ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-white/90 dark:bg-slate-900/90 text-gray-700 dark:text-gray-300 border-white/20 hover:bg-gray-50'">
                🚗 Traffic
            </button>

            <button @click="toggleLayer('heatmap')" 
                    class="p-3 rounded-xl backdrop-blur-md border transition-all shadow-lg font-bold text-xs flex items-center gap-2"
                    :class="activeLayer === 'heatmap' ? 'bg-orange-600 text-white border-orange-500' : 'bg-white/90 dark:bg-slate-900/90 text-gray-700 dark:text-gray-300 border-white/20 hover:bg-gray-50'">
                🔥 Kepadatan
            </button>
            
            <button @click="toggleLayer('standard')" 
                    class="p-3 rounded-xl backdrop-blur-md border transition-all shadow-lg font-bold text-xs flex items-center gap-2"
                    :class="activeLayer === 'standard' ? 'bg-black text-white border-black' : 'bg-white/90 dark:bg-slate-900/90 text-gray-700 dark:text-gray-300 border-white/20 hover:bg-gray-50'">
                🗺️ Standard
            </button>
        </div>

        <div x-show="selectedPlace" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10 scale-90"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             class="absolute bottom-6 left-6 right-6 lg:left-auto lg:right-6 lg:w-80 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-4 z-[50] ring-1 ring-black/5"
             style="display: none;">
            
            <template x-if="selectedPlace">
                <div>
                    <div class="relative h-40 rounded-2xl overflow-hidden mb-4 group">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
                        <img :src="selectedPlace.image_url" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        
                        <button @click="selectedPlace = null; selectedId = null" class="absolute top-2 right-2 bg-black/40 text-white p-1.5 rounded-full hover:bg-black/80 backdrop-blur-sm z-20 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <div class="absolute bottom-3 left-3 z-20">
                            <span class="text-[10px] font-bold bg-white/20 backdrop-blur-md border border-white/30 text-white px-2 py-1 rounded-lg" x-text="selectedPlace.category"></span>
                        </div>
                    </div>

                    <h3 class="font-black text-xl text-gray-900 dark:text-white leading-tight" x-text="selectedPlace.name"></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                        📍 <span x-text="selectedPlace.address"></span>
                    </p>
                    
                    <div class="flex gap-2 mt-4">
                        <a :href="'/place/' + selectedPlace.id" class="flex-1 py-3 bg-black dark:bg-white text-white dark:text-black font-bold rounded-xl text-sm shadow-lg hover:scale-[1.02] transition text-center">
                            Detail
                        </a>
                        <button class="px-4 py-3 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl font-bold text-sm hover:bg-indigo-200 transition">
                            🗺️ Rute
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mapboxComponent', (placesData) => ({
                map: null,
                markers: [],
                places: placesData,
                selectedId: null,
                selectedPlace: null,
                activeLayer: 'standard', // standard, traffic, heatmap

                // --- GANTI TOKEN INI DENGAN TOKEN MAPBOX ANDA ---
                accessToken: 'pk.eyJ1IjoiYW1haGVlZW4iLCJhIjoiY21rNWxjYzJsMGt3YzNocHd4cWN5dDA0ZyJ9.ywMaHVQIR3VvID3cVIo8Fw”', 

                initMap() {
                    mapboxgl.accessToken = 'pk.eyJ1IjoiYW1haGVlZW4iLCJhIjoiY21rNWxjYzJsMGt3YzNocHd4cWN5dDA0ZyJ9.ywMaHVQIR3VvID3cVIo8Fw”'; // GANTI TOKEN DISINI JUGA!

                    this.map = new mapboxgl.Map({
                        container: 'map',
                        style: 'mapbox://styles/mapbox/streets-v12', // Style default
                        center: [106.802870, -6.244229], // Jakarta Selatan
                        zoom: 13,
                        pitch: 45, // Efek 3D
                    });

                    this.map.addControl(new mapboxgl.NavigationControl(), 'bottom-right');

                    this.map.on('load', () => {
                        this.addHeatmapSource();
                        this.addMarkers();
                    });

                    // Listener untuk update data dari Livewire (Search/Filter)
                    this.$watch('places', (newPlaces) => {
                        this.markers.forEach(m => m.remove()); // Hapus marker lama
                        this.markers = [];
                        this.addMarkers(newPlaces);
                        this.updateHeatmapSource(newPlaces); // Update data heatmap
                    });
                },

                addMarkers(data = this.places) {
                    if (this.activeLayer === 'heatmap') return; // Jangan show marker kalau lagi mode heatmap

                    data.forEach(place => {
                        // Create Custom HTML Marker
                        const el = document.createElement('div');
                        el.className = 'custom-marker';
                        el.style.backgroundImage = `url(${place.image_url})`;
                        el.style.width = '40px';
                        el.style.height = '40px';

                        // Add to Map
                        const marker = new mapboxgl.Marker(el)
                            .setLngLat([place.longitude, place.latitude])
                            .addTo(this.map);

                        // Click Event
                        el.addEventListener('click', () => {
                            this.flyToLocation(place.latitude, place.longitude, place.id);
                        });

                        this.markers.push(marker);
                    });
                },

                flyToLocation(lat, lng, id) {
                    this.map.flyTo({
                        center: [lng, lat],
                        zoom: 16,
                        pitch: 60,
                        bearing: 20,
                        essential: true,
                        speed: 1.2
                    });
                    this.selectedId = id;
                    this.selectedPlace = this.places.find(p => p.id === id);
                },

                toggleLayer(layerType) {
                    this.activeLayer = layerType;

                    if (layerType === 'traffic') {
                        // Ganti ke style Traffic
                        this.map.setStyle('mapbox://styles/mapbox/traffic-day-v2');
                        this.map.once('style.load', () => {
                            this.addMarkers(); // Marker perlu di-add ulang setelah ganti style
                            this.addHeatmapSource(); // Source juga
                        });
                    } 
                    else if (layerType === 'heatmap') {
                        // Ganti ke style Dark agar heatmap menyala
                        this.map.setStyle('mapbox://styles/mapbox/dark-v11');
                        this.map.once('style.load', () => {
                            this.addHeatmapSource(); 
                            this.enableHeatmapLayer();
                            // Sembunyikan marker biasa
                            this.markers.forEach(m => m.remove());
                        });
                    } 
                    else {
                        // Balik ke Standard
                        this.map.setStyle('mapbox://styles/mapbox/streets-v12');
                        this.map.once('style.load', () => {
                            this.addMarkers();
                            this.addHeatmapSource();
                        });
                    }
                },

                // --- HEATMAP LOGIC ---
                
                getGeoJson(data) {
                    return {
                        type: 'FeatureCollection',
                        features: data.map(place => ({
                            type: 'Feature',
                            properties: {
                                // Konversi 'penuh' jadi angka tinggi agar merah
                                intensity: place.crowd_level === 'penuh' ? 1 : (place.crowd_level === 'ramai' ? 0.6 : 0.2)
                            },
                            geometry: {
                                type: 'Point',
                                coordinates: [place.longitude, place.latitude]
                            }
                        }))
                    };
                },

                addHeatmapSource() {
                    if (this.map.getSource('crowd-data')) return;

                    this.map.addSource('crowd-data', {
                        type: 'geojson',
                        data: this.getGeoJson(this.places)
                    });
                },

                updateHeatmapSource(newData) {
                    const source = this.map.getSource('crowd-data');
                    if (source) {
                        source.setData(this.getGeoJson(newData));
                    }
                },

                enableHeatmapLayer() {
                    // 1. Layer Heatmap (Blurry)
                    this.map.addLayer({
                        id: 'crowd-heat',
                        type: 'heatmap',
                        source: 'crowd-data',
                        paint: {
                            // Bobot berdasarkan properti 'intensity'
                            'heatmap-weight': [
                                'interpolate', ['linear'], ['get', 'intensity'],
                                0, 0,
                                1, 1
                            ],
                            // Warna dari Hijau (sepi) ke Merah (padat)
                            'heatmap-color': [
                                'interpolate', ['linear'], ['heatmap-density'],
                                0, 'rgba(33,102,172,0)',
                                0.2, 'rgb(103,169,207)',
                                0.4, 'rgb(209,229,240)',
                                0.6, 'rgb(253,219,199)',
                                0.8, 'rgb(239,138,98)',
                                1, 'rgb(178,24,43)'
                            ],
                            'heatmap-radius': 30,
                            'heatmap-opacity': 0.8
                        }
                    });

                    // 2. Layer Titik (Circle) untuk zoom dekat
                    this.map.addLayer({
                        id: 'crowd-point',
                        type: 'circle',
                        source: 'crowd-data',
                        minzoom: 14,
                        paint: {
                            'circle-radius': 8,
                            'circle-color': [
                                'interpolate', ['linear'], ['get', 'intensity'],
                                0.2, '#67a9cf',
                                1, '#b2182b'
                            ],
                            'circle-stroke-color': 'white',
                            'circle-stroke-width': 2,
                            'circle-opacity': 0.8
                        }
                    });
                }
            }))
        })
    </script>
</div>