<x-layouts.app>
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 p-4 md:p-8">
        
        <div class="max-w-7xl mx-auto space-y-8">
            
            {{-- HEADER DASHBOARD --}}
            <div class="relative overflow-hidden rounded-[2rem] p-8 md:p-12 text-white shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-r from-zinc-900 via-indigo-900 to-zinc-900 animate-gradient-x"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                
                <div class="relative z-10">
                    <span class="inline-block px-3 py-1 rounded-full bg-red-500/20 backdrop-blur-md border border-red-500/30 text-red-400 text-xs font-bold mb-3 tracking-wider">
                        🛡️ ADMINISTRATOR CONTROL ROOM
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 font-syne">
                        Dashboard Overview
                    </h1>
                    <p class="text-indigo-200 text-lg">Pantau traffic, viralitas, dan verifikasi bisnis dalam satu layar.</p>
                </div>
            </div>

            {{-- 1. METRICS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- Traffic --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-3xl relative overflow-hidden group hover:border-indigo-500/50 transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[10px] text-zinc-500 dark:text-zinc-400 uppercase font-bold tracking-wider">Total Views</p>
                            <h3 class="text-3xl font-black text-zinc-900 dark:text-white mt-1">{{ $metrics['traffic'] }}</h3>
                        </div>
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-indigo-500">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="h-1.5 w-full bg-zinc-100 dark:bg-zinc-800 rounded-full mt-2 overflow-hidden">
                        <div class="h-full bg-indigo-500 w-3/4 group-hover:w-full transition-all duration-1000"></div>
                    </div>
                </div>

                {{-- Viral Spots --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-3xl group hover:border-orange-500/50 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-[10px] text-zinc-500 dark:text-zinc-400 uppercase font-bold tracking-wider">Spot Viral</p>
                            <h3 class="text-3xl font-black text-zinc-900 dark:text-white mt-1">{{ $metrics['viral_spots'] }}</h3>
                        </div>
                        <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-xl text-orange-500">
                            <i class="fa-solid fa-fire"></i>
                        </div>
                    </div>
                    <div class="text-xs text-orange-500 flex items-center gap-1 mt-2 font-bold">
                        Siaga 1 (Penuh) <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>

                {{-- Weather --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-3xl">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-[10px] text-zinc-500 dark:text-zinc-400 uppercase font-bold tracking-wider">Cuaca Jaksel</p>
                            <h3 class="text-3xl font-black text-zinc-900 dark:text-white mt-1 flex items-center gap-2">
                                {{ $metrics['weather']['temp'] }}° <span class="text-lg text-zinc-400">C</span>
                            </h3>
                        </div>
                        <div class="text-blue-400 text-2xl animate-pulse">
                            <i class="fa-solid fa-cloud"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-end mt-2 text-xs text-zinc-500 font-medium">
                        <span>{{ $metrics['weather']['condition'] }}</span>
                        <span>Rain: {{ $metrics['weather']['rain_chance'] }}</span>
                    </div>
                </div>

                {{-- Most Wanted --}}
                <div class="bg-gradient-to-br from-indigo-600 to-violet-700 p-6 rounded-3xl text-white relative overflow-hidden shadow-lg shadow-indigo-500/20">
                    <div class="absolute -right-4 -bottom-4 text-9xl opacity-10 rotate-12">🏆</div>
                    <p class="text-[10px] text-indigo-200 uppercase font-bold tracking-wider mb-1">Most Wanted</p>
                    <h3 class="text-lg font-bold leading-tight mb-3 uppercase truncate">{{ $metrics['most_wanted']['name'] }}</h3>
                    <div class="flex items-center gap-2 text-xs text-indigo-100 bg-white/20 px-3 py-1.5 rounded-lg w-fit backdrop-blur-sm">
                        <i class="fa-regular fa-clock"></i> Wait: {{ $metrics['most_wanted']['waitlist'] }}
                    </div>
                </div>
            </div>

            {{-- 2. TABEL VERIFIKASI --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Kolom Kiri: Tabel Antrian --}}
                <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-[2rem] shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8 border-b border-zinc-100 dark:border-zinc-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-clipboard-check text-indigo-500"></i> Permintaan Verifikasi
                            </h2>
                            <p class="text-sm text-zinc-500 mt-1">Review klaim kepemilikan bisnis dari user.</p>
                        </div>
                        
                        @if(isset($pendingClaims) && $pendingClaims->count() > 0)
                            <span class="px-4 py-1.5 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-500 rounded-full text-xs font-bold animate-pulse">
                                {{ $pendingClaims->count() }} Menunggu Review
                            </span>
                        @else
                            <span class="px-4 py-1.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-500 rounded-full text-xs font-bold">
                                Semua Aman (0 Pending)
                            </span>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 text-[10px] uppercase tracking-wider font-bold">
                                <tr>
                                    <th class="px-6 py-4">Bisnis</th>
                                    <th class="px-6 py-4">Pemohon</th>
                                    <th class="px-6 py-4">Waktu Request</th>
                                    <th class="px-6 py-4 text-right">Keputusan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                @if(isset($pendingClaims) && $pendingClaims->count() > 0)
                                    @foreach($pendingClaims as $claim)
                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition group">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-sm text-zinc-900 dark:text-white group-hover:text-indigo-500 transition">{{ $claim->name }}</div>
                                                <div class="text-xs text-zinc-500 truncate max-w-[150px]">{{ $claim->category }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0">
                                                        {{ substr($claim->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-xs text-zinc-900 dark:text-white">{{ $claim->user->name }}</div>
                                                        <div class="text-[10px] text-zinc-500">{{ $claim->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-xs text-zinc-500 font-medium">
                                                {{ $claim->updated_at->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form action="{{ route('admin.approve', $claim->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-100 hover:bg-green-500 text-green-600 hover:text-white rounded-lg transition shadow-sm hover:shadow-green-500/30" title="Setujui">
                                                            <i class="fa-solid fa-check text-xs"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('admin.reject', $claim->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" onclick="return confirm('Yakin ingin menolak klaim ini?')" class="w-8 h-8 flex items-center justify-center bg-red-100 hover:bg-red-500 text-red-600 hover:text-white rounded-lg transition shadow-sm hover:shadow-red-500/30" title="Tolak">
                                                            <i class="fa-solid fa-xmark text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center text-zinc-400">
                                            <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fa-solid fa-inbox text-2xl opacity-30"></i>
                                            </div>
                                            <p class="font-medium text-sm">Tidak ada permintaan pending saat ini.</p>
                                            <p class="text-xs opacity-60">Cek lagi nanti ya!</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Kolom Kanan: Latest Updates --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-[2rem] p-6 h-fit">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-yellow-500"></i> Aktivitas Terbaru
                    </h2>
                    <div class="space-y-6">
                        @foreach($updates as $update)
                        <div class="flex gap-4 items-start group cursor-pointer">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 relative">
                                <img src="{{ $update['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition"></div>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded uppercase tracking-wide">{{ $update['category'] }}</span>
                                <h4 class="font-bold text-sm text-zinc-700 dark:text-zinc-200 mt-1 leading-snug group-hover:text-indigo-600 dark:group-hover:text-white transition">
                                    {{ $update['title'] }}
                                </h4>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Chart Density --}}
                    <div class="mt-8 pt-8 border-t border-zinc-100 dark:border-zinc-800">
                        <h3 class="text-xs font-bold uppercase text-zinc-400 mb-4 tracking-wider">Traffic Analyst</h3>
                        <div class="relative w-full h-40">
                            <canvas id="densityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART DATA HANDLER --}}
    <div id="chart-data-source" 
         data-labels="{{ json_encode($chartData['labels']) }}" 
         data-values="{{ json_encode($chartData['data']) }}"
         class="hidden">
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dataSource = document.getElementById('chart-data-source');
            if(!dataSource) return;

            const labels = JSON.parse(dataSource.dataset.labels);
            const dataValues = JSON.parse(dataSource.dataset.values);

            const ctx = document.getElementById('densityChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); 
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Visits',
                        data: dataValues,
                        backgroundColor: gradient,
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        pointRadius: 0, 
                        pointHoverRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { display: false },
                        x: { display: false }
                    }
                }
            });
        });
    </script>
</x-layouts.app>