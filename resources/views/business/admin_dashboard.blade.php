<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-8 p-4">
        
        <div class="relative overflow-hidden rounded-3xl p-8 md:p-12 text-white shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 animate-gradient-x"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
            
            <div class="relative z-10">
                <span class="inline-block px-3 py-1 rounded-full bg-red-500/20 backdrop-blur-md border border-red-500/30 text-red-400 text-xs font-bold mb-2 tracking-wider">
                    🛡️ ADMINISTRATOR MODE
                </span>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 font-syne">
                    Dashboard Overview
                </h1>
                <p class="text-slate-300 text-lg">Pantau performa seluruh platform Kalcer.id dari sini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-zinc-900 border border-zinc-800 p-5 rounded-2xl relative group overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-[10px] text-zinc-400 uppercase font-bold tracking-wider">Total Traffic</p>
                        <h3 class="text-3xl font-black text-white mt-1">{{ $metrics['traffic'] }}</h3>
                    </div>
                    <div class="p-2 bg-zinc-800 rounded-lg text-green-400">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="h-1 w-full bg-zinc-700 rounded-full mt-2 overflow-hidden">
                    <div class="h-full bg-green-500 w-3/4"></div>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 p-5 rounded-2xl">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-[10px] text-zinc-400 uppercase font-bold tracking-wider">Spot Viral</p>
                        <h3 class="text-3xl font-black text-white mt-1">{{ $metrics['viral_spots'] }}</h3>
                    </div>
                    <div class="p-2 bg-zinc-800 rounded-lg text-orange-400">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                </div>
                <div class="text-xs text-orange-400 flex items-center gap-1 mt-2 font-bold">
                    Siaga 1 (Penuh) <i class="fa-solid fa-arrow-right"></i>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 p-5 rounded-2xl">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-[10px] text-zinc-400 uppercase font-bold tracking-wider">Cuaca Jaksel</p>
                        <h3 class="text-3xl font-black text-white mt-1 flex items-center gap-2">
                            {{ $metrics['weather']['temp'] }}° <span class="text-lg text-zinc-500">C</span>
                        </h3>
                    </div>
                    <div class="text-indigo-400 text-2xl">
                        <i class="fa-solid fa-cloud"></i>
                    </div>
                </div>
                <div class="flex justify-between items-end mt-2 text-xs text-zinc-400 font-medium">
                    <span>{{ $metrics['weather']['condition'] }}</span>
                    <span>Hujan: {{ $metrics['weather']['rain_chance'] }}</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-violet-700 p-5 rounded-2xl text-white relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-9xl opacity-10 rotate-12">🏆</div>
                <p class="text-[10px] text-indigo-200 uppercase font-bold tracking-wider mb-1">Most Wanted</p>
                <h3 class="text-lg font-bold leading-tight mb-3 uppercase truncate">{{ $metrics['most_wanted']['name'] }}</h3>
                <div class="flex items-center gap-2 text-xs text-indigo-100 bg-white/20 px-2 py-1 rounded-lg w-fit">
                    <i class="fa-regular fa-clock"></i> Wait: {{ $metrics['most_wanted']['waitlist'] }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-white">Analisis Kepadatan (Real-time)</h2>
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                    </span>
                </div>
                <div class="relative w-full h-72">
                    <canvas id="densityChart"></canvas>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-400"></i> Latest Updates
                </h2>
                <div class="space-y-4">
                    @foreach($updates as $update)
                    <div class="flex gap-4 items-start group cursor-pointer">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0">
                            <img src="{{ $update['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-indigo-400 bg-indigo-400/10 px-2 py-0.5 rounded uppercase">{{ $update['category'] }}</span>
                            <h4 class="font-bold text-sm text-zinc-200 mt-1 leading-snug group-hover:text-white transition">
                                {{ $update['title'] }}
                            </h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="chart-data-source" 
         data-labels="{{ json_encode($chartData['labels']) }}" 
         data-values="{{ json_encode($chartData['data']) }}"
         class="hidden">
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil data dari elemen HTML di atas
            const dataSource = document.getElementById('chart-data-source');
            
            // Parsing JSON dari atribut data-labels dan data-values
            const labels = JSON.parse(dataSource.dataset.labels);
            const dataValues = JSON.parse(dataSource.dataset.values);

            const ctx = document.getElementById('densityChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); 
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pengunjung Active',
                        data: dataValues,
                        backgroundColor: gradient,
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        pointBackgroundColor: '#18181b',
                        pointBorderColor: '#6366f1',
                        pointHoverBackgroundColor: '#6366f1',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(24, 24, 27, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                        }
                    },
                    scales: {
                        y: { 
                            grid: { color: '#27272a', borderDash: [5, 5] }, 
                            ticks: { color: '#71717a', font: {family: "'Plus Jakarta Sans', sans-serif"} } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#71717a', font: {family: "'Plus Jakarta Sans', sans-serif"} } 
                        }
                    }
                }
            });
        });
    </script>
</x-layouts.app>