<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\HangoutPlace;
use Illuminate\Support\Facades\Auth;

new 
#[Layout('components.layouts.app')]
#[Title('Peta Sebaran')]
class extends Component {
    public $places;
    public $search = '';
    public $activeCategory = 'Semua'; // State untuk kategori aktif

    public function mount()
    {
        $this->places = HangoutPlace::all();
    }

    // Satu fungsi utama untuk handle Search & Filter
    public function filterPlaces()
    {
        $query = HangoutPlace::query();

        // 1. Filter Search String
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // 2. Filter Category (Jika bukan 'Semua')
        if ($this->activeCategory !== 'Semua') {
            $query->where('category', 'like', '%' . $this->activeCategory . '%');
        }

        $this->places = $query->get();
        
        // Kirim event ke JS untuk gambar ulang marker
        $this->dispatch('update-map-markers', places: $this->places);
    }

    // Trigger saat search diketik
    public function updatedSearch()
    {
        $this->filterPlaces();
    }

    // Trigger saat tombol kategori diklik
    public function setCategory($cat)
    {
        $this->activeCategory = $cat;
        $this->filterPlaces();
    }
}; ?>

<div class="flex flex-col lg:flex-row h-[calc(100vh-64px)] bg-white dark:bg-zinc-900 overflow-hidden relative">

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #52525b; border-radius: 20px; }
        
        /* Override Mapbox Popup Style untuk Dark Mode */
        .mapboxgl-popup-content {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .mapboxgl-popup-tip {
            border-top-color: #18181b !important; /* Zinc-900 */
        }
    </style>

    <div class="w-full lg:w-96 bg-white dark:bg-zinc-900 flex flex-col border-r border-zinc-200 dark:border-zinc-800 z-20 shadow-xl lg:shadow-none h-[40vh] lg:h-full order-2 lg:order-1 transition-all duration-300">
        
        <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 z-10">
            <div class="relative group">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 group-focus-within:text-indigo-500 transition"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari cafe, taman, atau daerah..." 
                    class="w-full pl-10 pr-10 py-2.5 bg-zinc-100 dark:bg-zinc-800 border-none rounded-xl text-zinc-900 dark:text-white placeholder-zinc-500 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition">
                
                <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <i class="fa-solid fa-circle-notch fa-spin text-indigo-500"></i>
                </div>
            </div>
            
            <div class="flex gap-2 mt-3 overflow-x-auto no-scrollbar pb-1">
                @foreach(['Semua', 'Coffee', 'Taman', 'WFC', 'Date'] as $cat)
                    <button wire:click="setCategory('{{ $cat }}')" 
                        class="px-4 py-1.5 text-xs font-bold rounded-full whitespace-nowrap transition border {{ $activeCategory === $cat ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700' }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-2 space-y-2 custom-scrollbar bg-zinc-50 dark:bg-zinc-900/50">
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

                        <div class="flex items-center justify-center">
                            <button class="w-8 h-8 rounded-full bg-zinc-50 dark:bg-zinc-800 text-zinc-400 group-hover:bg-indigo-600 group-hover:text-white transition flex items-center justify-center">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="h-full flex flex-col items-center justify-center text-center opacity-50">
                    <i class="fa-solid fa-map-location-dot text-4xl text-zinc-400 mb-2"></i>
                    <p class="text-zinc-500 text-sm font-medium">Tidak ada tempat ditemukan.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="flex-1 relative order-1 lg:order-2 h-[60vh] lg:h-full bg-zinc-100 dark:bg-zinc-800">
        <div id="map" class="w-full h-full absolute inset-0 z-0"></div>
        
        <div class="absolute top-4 right-4 z-10 flex flex-col gap-2">
            <button onclick="map.flyTo({center: userLocation, zoom: 14})" class="w-10 h-10 bg-white dark:bg-zinc-900 rounded-xl shadow-lg flex items-center justify-center text-zinc-700 dark:text-white hover:bg-zinc-50 dark:hover:bg-zinc-800 transition ring-1 ring-zinc-900/5" title="Lokasi Saya">
                <i class="fa-solid fa-crosshairs"></i>
            </button>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-zinc-900/50 to-transparent pointer-events-none"></div>
    </div>
    
    <div id="map-data" data-places="{{ json_encode($places) }}" class="hidden"></div>
</div>

@script
<script>
    // TOKEN ANDA
    mapboxgl.accessToken = 'pk.eyJ1IjoiYW1haGVlZW4iLCJhIjoiY21rNWxjYzJsMGt3YzNocHd4cWN5dDA0ZyJ9.ywMaHVQIR3VvID3cVIo8Fw';

    let map;
    // Ubah markers jadi Object biar bisa diakses by ID
    let markers = {}; 
    let userLocation = [106.8060, -6.2425]; // Default Jaksel
    
    function initMap(placesData) {
        map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/dark-v11',
            center: userLocation,
            zoom: 12,
            pitch: 20
        });

        // Tambahkan Navigasi Zoom (+/-)
        map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'bottom-right');

        map.on('load', () => {
            map.resize();
            
            // User Location Marker
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    userLocation = [pos.coords.longitude, pos.coords.latitude];
                    
                    // Create Pulsing Dot for User
                    const el = document.createElement('div');
                    el.className = 'flex items-center justify-center w-6 h-6 rounded-full bg-indigo-500 border-2 border-white shadow-lg shadow-indigo-500/50';
                    el.innerHTML = '<div class="w-2 h-2 bg-white rounded-full"></div>';
                    
                    new mapboxgl.Marker(el).setLngLat(userLocation).addTo(map);
                });
            }

            renderMarkers(placesData);
        });
    }

    function renderMarkers(places) {
        // Hapus marker lama
        Object.values(markers).forEach(marker => marker.remove());
        markers = {}; // Reset

        places.forEach(place => {
            const el = document.createElement('div');
            const color = place.viral_score > 90 ? '#ec4899' : (place.viral_score > 75 ? '#8b5cf6' : '#64748b');
            
            // Icon Marker Lebih Bagus
            el.innerHTML = `
                <div class="group relative flex flex-col items-center justify-center cursor-pointer transition-transform hover:scale-110">
                    <div style="background-color: ${color};" class="w-8 h-8 rounded-full border-[3px] border-white shadow-lg flex items-center justify-center text-white text-xs">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div style="background-color: ${color};" class="absolute -bottom-1 w-2 h-2 rotate-45"></div>
                </div>
            `;
            
            // Create Popup Instance (Belum ditempel ke map)
            // Menggunakan HTML kustom agar sesuai Dark Mode / Glassmorphism
            const popup = new mapboxgl.Popup({ offset: 20, closeButton: false, maxWidth: '240px' })
                .setHTML(`
                    <div class="bg-zinc-900/95 backdrop-blur-md border border-zinc-700 rounded-xl overflow-hidden shadow-2xl">
                        <div class="relative h-24 w-full">
                            <img src="${place.image_url}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 to-transparent"></div>
                            <span class="absolute bottom-2 left-3 text-[10px] font-bold text-white bg-black/50 px-2 py-0.5 rounded backdrop-blur-sm">${place.category}</span>
                        </div>
                        <div class="p-3">
                            <h3 class="font-bold text-sm text-white mb-1 leading-tight">${place.name}</h3>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] text-zinc-400">Viral Score</span>
                                <span class="text-xs font-bold text-pink-500">${place.viral_score}%</span>
                            </div>
                            <a href="/place/${place.id}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2 rounded-lg transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                `);

            // Klik Marker langsung di Peta
            el.addEventListener('click', () => {
                map.flyTo({ center: [place.longitude, place.latitude], zoom: 16 });
                popup.addTo(map); // Tampilkan popup
            });

            const marker = new mapboxgl.Marker(el)
                .setLngLat([place.longitude, place.latitude])
                .setPopup(popup) // Bind popup ke marker
                .addTo(map);

            // Simpan marker ke object dengan Key ID tempat
            markers[place.id] = marker;
        });
    }

    // UPDATE: Fungsi ini dipanggil saat item di Sidebar diklik
    window.flyToLocation = (element) => {
        const lng = parseFloat(element.dataset.lng);
        const lat = parseFloat(element.dataset.lat);
        const id = element.dataset.id;

        // 1. Terbang ke lokasi
        map.flyTo({
            center: [lng, lat],
            zoom: 16,
            essential: true,
            pitch: 30
        });
        
        // 2. Buka Popup Marker yang Sesuai
        const targetMarker = markers[id];
        if (targetMarker) {
            targetMarker.togglePopup(); // Buka popupnya
        }
    };

    const rawData = document.getElementById('map-data').dataset.places;
    initMap(JSON.parse(rawData));

    // Listen Livewire Update
    Livewire.on('update-map-markers', ({ places }) => {
        renderMarkers(places);
    });

</script>
@endscript