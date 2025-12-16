<?php

use App\Models\HangoutPlace;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    public HangoutPlace $place;

    public function mount(HangoutPlace $place)
    {
        $this->place = $place;
    }
}; ?>

<div class="pb-20">
    <div class="relative w-full h-[50vh] md:h-[60vh] overflow-hidden">
        <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-6 md:p-12 max-w-7xl mx-auto text-white">
            <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3 inline-block shadow-lg">
                {{ $place->category }}
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold drop-shadow-md">{{ $place->name }}</h1>
            <p class="text-gray-200 mt-2 flex items-center gap-2 text-lg font-medium">
                📍 {{ $place->address }}
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-purple-500 pl-3">Tentang Tempat Ini</h2>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        {{ $place->description }}
                    </p>

                    <div class="mt-8">
                        <h3 class="font-bold text-gray-900 mb-3">Fasilitas</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($place->facilities ?? [] as $facility)
                                <span class="px-3 py-1.5 rounded-lg bg-gray-50 text-gray-600 text-sm font-medium border border-gray-100">
                                    ✨ {{ $facility }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-2 overflow-hidden">
                    <div id="map" class="w-full h-[350px] rounded-xl z-0"></div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-bl-full -mr-4 -mt-4 z-0"></div>
                    
                    <div class="relative z-10">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Viral Score</div>
                        <div class="flex items-end gap-1 mt-1">
                            <span class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">{{ $place->viral_score }}</span>
                            <span class="text-gray-400 font-bold mb-1">/100</span>
                        </div>

                        <hr class="my-4 border-gray-100">

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 text-sm">Crowd Level</span>
                                @php
                                    $colors = [
                                        'sepi' => 'bg-green-100 text-green-700',
                                        'sedang' => 'bg-yellow-100 text-yellow-700',
                                        'ramai' => 'bg-orange-100 text-orange-700',
                                        'penuh' => 'bg-red-100 text-red-700'
                                    ];
                                    $bgClass = $colors[$place->crowd_level] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="{{ $bgClass }} px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    {{ $place->crowd_level }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 text-sm">Operational</span>
                                <span class="font-medium text-gray-900 text-sm">{{ $place->operational_hours }}</span>
                            </div>
                        </div>

                        <a href="https://maps.google.com/?q={{ $place->latitude }},{{ $place->longitude }}" target="_blank" class="mt-6 block w-full py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-center shadow-lg hover:shadow-purple-200 transition transform hover:-translate-y-0.5">
                            Navigasi Google Maps 🚀
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            initMap();
        });
        document.addEventListener('DOMContentLoaded', () => {
            initMap();
        });

        function initMap() {
            var container = L.DomUtil.get('map');
            if(container != null) container._leaflet_id = null; else return;

            var lat = {{ $place->latitude }};
            var lng = {{ $place->longitude }};
            
            // Menggunakan tiles yang lebih clean/terang (CartoDB Positron) agar sesuai tema "Clean"
            var map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 15);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CARTO',
                maxZoom: 20
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup("<b>{{ $place->name }}</b>")
                .openPopup();
        }
    </script>
</div>