<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\HangoutPlace;
use Illuminate\Support\Facades\Auth;

new 
#[Layout('components.layouts.app')]
#[Title('Maps')]
class extends Component {
    public $places;
    public $search = '';
    public $activeCategory = 'Semua';

    public function mount()
    {
        $this->places = HangoutPlace::all();
    }

    public function filterPlaces()
    {
        $query = HangoutPlace::query();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->activeCategory !== 'Semua') {
            $query->where('category', 'like', '%' . $this->activeCategory . '%');
        }

        $this->places = $query->get();
        
        // Kirim data baru ke Mapbox
        $this->dispatch('update-map-markers', places: $this->places);
    }

    public function updatedSearch() { $this->filterPlaces(); }
    public function setCategory($cat) { 
        $this->activeCategory = $cat; 
        $this->filterPlaces(); 
    }
}; ?>

{{-- ROOT ELEMENT (HANYA BOLEH ADA SATU DIV UTAMA) --}}
<div class="flex flex-col lg:flex-row h-[calc(100vh-64px)] bg-white dark:bg-zinc-900 overflow-hidden relative">

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #52525b; border-radius: 20px; }
        
        /* Popup Custom Style */
        .mapboxgl-popup-content { background: transparent !important; box-shadow: none !important; padding: 0 !important; }
        .mapboxgl-popup-tip { border-top-color: #18181b !important; }
        
        /* Hide mapbox logo info for cleaner look (optional) */
        .mapboxgl-ctrl-bottom-left, .mapboxgl-ctrl-bottom-right { opacity: 0.6; transform: scale(0.8); transform-origin: bottom; }
    </style>

    <div class="w-full lg:w-96 bg-white dark:bg-zinc-900 flex flex-col border-r border-zinc-200 dark:border-zinc-800 z-20 shadow-2xl h-[45vh] lg:h-full order-2 lg:order-1 transition-all duration-300 relative">
        
        <div class="lg:hidden w-full flex justify-center py-2 bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800 rounded-t-3xl -mt-4 shadow-[0_-5px_15px_rgba(0,0,0,0.1)] z-30">
            <div class="w-12 h-1.5 bg-zinc-300 dark:bg-zinc-700 rounded-full"></div>
        </div>

        <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 z-10">
            <div class="relative group">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 group-focus-within:text-indigo-500 transition"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari cafe, taman..." 
                    class="w-full pl-10 pr-10 py-2.5 bg-zinc-100 dark:bg-zinc-800 border-none rounded-xl text-zinc-900 dark:text-white placeholder-zinc-500 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition">
                <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <i class="fa-solid fa-circle-notch fa-spin text-indigo-500"></i>
                </div>
            </div>
            
            <div class="flex gap-2 mt-3 overflow-x-auto no-scrollbar pb-1">
                @foreach(['Semua', 'Coffee Shop', 'Public Park', 'Culinary', 'Creative Space'] as $cat)
                    <button wire:click="setCategory('{{ $cat }}')" 
                        class="px-4 py-1.5 text-xs font-bold rounded-full whitespace-nowrap transition border {{ $activeCategory === $cat ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/30' : 'bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700' }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-2 space-y-2 custom-scrollbar bg-zinc-50 dark:bg-zinc-900/50 pb-20 lg:pb-2">
            @if(count($places) > 0)
                <div class="flex justify-between items-center px-2 mt-2">
                    <h3 class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Hasil Pencarian</h3>
                    <span class="text-[10px] bg-zinc-200 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 px-2 py-0.5 rounded-full">{{ count($places) }} Tempat</span>
                </div>

                @foreach($places as $place)
                    <div onclick="flyToLocation(this)" 
                         data-id="{{ $place->id }}"
                         data-lng="{{ $place->longitude }}"
                         data-lat="{{ $place->latitude }}"
                         class="cursor-pointer group flex gap-3 p-3 rounded-2xl bg-white dark:bg-zinc-900 hover:bg-white dark:hover:bg-zinc-800 transition border border-zinc-100 dark:border-zinc-800 hover:border-indigo-500/50 hover:shadow-lg dark:hover:shadow-indigo-500/10">
                        
                        <div class="w-20 h-20 shrink-0 rounded-xl overflow-hidden bg-zinc-200 relative">
                            <img src="{{ $place->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-zinc-900 dark:text-white text-sm truncate group-hover:text-indigo-500 transition">{{ $place->name }}</h4>
                                <p class="text-xs text-zinc-500 truncate mb-1">{{ $place->category }}</p>
                            </div>
                            
                            <div class="flex items-center justify-between mt-1">
                                <div class="flex items-center gap-1 text-[10px] font-bold {{ $place->viral_score > 80 ? 'text-orange-500' : 'text-zinc-400' }}">
                                    <i class="fa-solid fa-fire"></i> {{ $place->viral_score }}% Viral
                                </div>
                                <span class="text-[10px] text-zinc-400 flex items-center gap-1">
                                    {{ number_format($place->profile_views) }} <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="h-full flex flex-col items-center justify-center text-center opacity-50 py-10">
                    <i class="fa-solid fa-map-location-dot text-4xl text-zinc-400 mb-2"></i>
                    <p class="text-zinc-500 text-sm font-medium">Tidak ada tempat ditemukan.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="flex-1 relative order-1 lg:order-2 h-[55vh] lg:h-full bg-zinc-100 dark:bg-zinc-800">
        <div id="map" class="w-full h-full absolute inset-0 z-0"></div>
        
        <div class="absolute top-4 right-4 z-10 flex flex-col gap-2">
            <button onclick="map.flyTo({center: userLocation, zoom: 14})" class="w-10 h-10 bg-white/90 dark:bg-zinc-900/90 backdrop-blur rounded-xl shadow-lg flex items-center justify-center text-zinc-700 dark:text-white hover:bg-zinc-50 dark:hover:bg-zinc-800 transition ring-1 ring-black/5" title="Lokasi Saya">
                <i class="fa-solid fa-crosshairs"></i>
            </button>
            <div class="bg-white/90 dark:bg-zinc-900/90 backdrop-blur rounded-xl shadow-lg p-2 flex flex-col items-center gap-1 ring-1 ring-black/5">
                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                <span class="text-[8px] font-bold uppercase text-zinc-500">Ramai</span>
            </div>
        </div>
    </div>
    
    <div id="map-data" data-places="{{ json_encode($places) }}" class="hidden"></div>
</div> {{-- PENUTUP DIV UTAMA (JANGAN ADA HTML LAIN SETELAH INI) --}}

{{-- SCRIPT DIJALANKAN DI LUAR DOM TAPI TETAP DALAM KONTEKS LIVEWIRE --}}
@script
<script>
    // Pastikan token ini benar
    mapboxgl.accessToken = 'pk.eyJ1IjoiYW1haGVlZW4iLCJhIjoiY21rNWxjYzJsMGt3YzNocHd4cWN5dDA0ZyJ9.ywMaHVQIR3VvID3cVIo8Fw';

    let map;
    let markers = {}; 
    let userLocation = [106.8060, -6.2425]; 

    const isDarkMode = document.documentElement.classList.contains('dark') || localStorage.getItem('theme') === 'dark';
    const mapStyle = isDarkMode ? 'mapbox://styles/mapbox/dark-v11' : 'mapbox://styles/mapbox/light-v11';

    function initMap(placesData) {
        map = new mapboxgl.Map({
            container: 'map',
            style: mapStyle, 
            center: userLocation,
            zoom: 12,
            pitch: 45, 
            bearing: -17.6,
        });

        map.addControl(new mapboxgl.NavigationControl({ showCompass: true }), 'bottom-right');

        map.on('load', () => {
            map.resize();

            // --- A. TRAFFIC LAYER ---
            map.addSource('mapbox-traffic', {
                type: 'vector',
                url: 'mapbox://mapbox.mapbox-traffic-v1'
            });
            map.addLayer({
                id: 'traffic',
                type: 'line',
                source: 'mapbox-traffic',
                'source-layer': 'traffic',
                paint: {
                    'line-width': 2,
                    'line-color': [
                        'match', ['get', 'congestion'],
                        'low', '#4ade80',    
                        'moderate', '#facc15', 
                        'heavy', '#ef4444',    
                        'severe', '#991b1b',   
                        '#000000'
                    ],
                    'line-opacity': 0.6 
                }
            });

            // --- B. HEATMAP ---
            const geoJsonData = {
                type: 'FeatureCollection',
                features: placesData.map(place => ({
                    type: 'Feature',
                    geometry: { type: 'Point', coordinates: [place.longitude, place.latitude] },
                    properties: { 
                        viral_score: place.viral_score, 
                        id: place.id 
                    }
                }))
            };

            map.addSource('places-heat', { type: 'geojson', data: geoJsonData });

            map.addLayer({
                id: 'places-heat-layer',
                type: 'heatmap',
                source: 'places-heat',
                maxzoom: 15,
                paint: {
                    'heatmap-weight': ['interpolate', ['linear'], ['get', 'viral_score'], 0, 0, 100, 1],
                    'heatmap-intensity': ['interpolate', ['linear'], ['zoom'], 0, 1, 15, 3],
                    'heatmap-color': [
                        'interpolate', ['linear'], ['heatmap-density'],
                        0, 'rgba(33,102,172,0)',
                        0.2, 'rgb(103,169,207)',
                        0.4, 'rgb(209,229,240)',
                        0.6, 'rgb(253,219,199)',
                        0.8, 'rgb(239,138,98)',
                        1, 'rgb(178,24,43)' 
                    ],
                    'heatmap-radius': ['interpolate', ['linear'], ['zoom'], 0, 2, 15, 20],
                    'heatmap-opacity': 0.7
                }
            });

            // --- C. RENDER MARKERS ---
            setupUserLocation();
            renderMarkers(placesData);
        });
    }

    function setupUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                userLocation = [pos.coords.longitude, pos.coords.latitude];
                const el = document.createElement('div');
                el.className = 'flex items-center justify-center w-6 h-6 rounded-full bg-indigo-500 border-2 border-white shadow-lg shadow-indigo-500/50 animate-pulse';
                el.innerHTML = '<div class="w-2 h-2 bg-white rounded-full"></div>';
                new mapboxgl.Marker(el).setLngLat(userLocation).addTo(map);
            });
        }
    }

    function renderMarkers(places) {
        Object.values(markers).forEach(marker => marker.remove());
        markers = {}; 

        places.forEach(place => {
            const el = document.createElement('div');
            const color = place.viral_score > 85 ? '#ef4444' : (place.viral_score > 60 ? '#f59e0b' : '#10b981');
            
            el.innerHTML = `
                <div class="group relative flex flex-col items-center justify-center cursor-pointer transition-transform hover:scale-125 duration-300">
                    <div style="background-color: ${color};" class="w-10 h-10 rounded-full border-[3px] border-white dark:border-zinc-800 shadow-xl flex items-center justify-center text-white text-sm relative z-10">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div style="background-color: ${color};" class="absolute bottom-0 w-3 h-3 rotate-45 mb-1 z-0"></div>
                    <div class="absolute -bottom-2 w-10 h-1 bg-black/30 blur-sm rounded-full"></div>
                </div>
            `;
            
            const popupHTML = `
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden shadow-2xl w-56 font-sans">
                    <div class="relative h-28 w-full">
                        <img src="${place.image_url}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                        <span class="absolute top-2 right-2 text-[10px] font-bold text-black bg-white/90 px-2 py-0.5 rounded-full shadow-sm">${place.category}</span>
                        <div class="absolute bottom-2 left-3">
                            <h3 class="font-bold text-sm text-white leading-tight shadow-black drop-shadow-md">${place.name}</h3>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400">
                                <i class="fa-solid fa-fire text-orange-500"></i> ${place.viral_score}%
                            </div>
                            <div class="flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400">
                                <i class="fa-solid fa-eye text-indigo-500"></i> ${place.profile_views}
                            </div>
                        </div>
                        <a href="/place/${place.id}" class="block w-full text-center bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-xs font-bold py-2 rounded-lg hover:opacity-90 transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            `;

            const popup = new mapboxgl.Popup({ offset: 25, closeButton: false, maxWidth: '300px' }).setHTML(popupHTML);

            el.addEventListener('click', () => {
                map.flyTo({ center: [place.longitude, place.latitude], zoom: 16, pitch: 50 });
                popup.addTo(map);
            });

            const marker = new mapboxgl.Marker(el)
                .setLngLat([place.longitude, place.latitude])
                .setPopup(popup)
                .addTo(map);

            markers[place.id] = marker;
        });
    }

    window.flyToLocation = (element) => {
        const lng = parseFloat(element.dataset.lng);
        const lat = parseFloat(element.dataset.lat);
        const id = element.dataset.id;
        map.flyTo({ center: [lng, lat], zoom: 17, essential: true, pitch: 50 });
        if (markers[id]) markers[id].togglePopup();
    };

    const rawData = document.getElementById('map-data').dataset.places;
    initMap(JSON.parse(rawData));

    Livewire.on('update-map-markers', ({ places }) => {
        renderMarkers(places);
        const geoJsonData = {
            type: 'FeatureCollection',
            features: places.map(place => ({
                type: 'Feature',
                geometry: { type: 'Point', coordinates: [place.longitude, place.latitude] },
                properties: { viral_score: place.viral_score, id: place.id }
            }))
        };
        if(map.getSource('places-heat')) {
            map.getSource('places-heat').setData(geoJsonData);
        }
    });
</script>
@endscript