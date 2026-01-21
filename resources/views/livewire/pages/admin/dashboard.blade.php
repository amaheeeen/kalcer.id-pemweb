<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\HangoutPlace;
use App\Models\Review;

new 
#[Layout('components.layouts.app')]
#[Title('Admin Command Center')]
class extends Component {
    use WithPagination;

    // --- ACTIONS ---
    
    // 1. Verifikasi Bisnis
    public function verifyPlace($id)
    {
        $place = HangoutPlace::find($id);
        $place->update(['is_verified' => true]);
        session()->flash('message', "✅ Bisnis '{$place->name}' berhasil diverifikasi!");
    }

    // 2. Hapus Bisnis
    public function deletePlace($id)
    {
        HangoutPlace::destroy($id);
        session()->flash('error', "🗑️ Bisnis berhasil dihapus dari database.");
    }

    // 3. Toggle Recommendation (Fitur Baru: Editor's Choice)
    public function toggleRecommendation($id)
    {
        $place = HangoutPlace::find($id);
        $newState = !$place->is_recommended;
        $place->update(['is_recommended' => $newState]);
        
        $msg = $newState ? "⭐ Ditambahkan ke Rekomendasi Admin!" : "Dicabut dari Rekomendasi.";
        session()->flash('message', $msg);
    }

    public function with()
    {
        // Security Check
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return [
            // Statistik Utama
            'total_users' => User::count(),
            'total_places' => HangoutPlace::count(),
            'total_reviews' => Review::count(),
            
            // Antrian Verifikasi
            'pending_places' => HangoutPlace::where('is_verified', false)->latest()->get(),
            
            // Top Viral (Leaderboard)
            'viral_places' => HangoutPlace::orderBy('viral_score', 'desc')->take(5)->get(),
            
            // Semua Data (Pagination)
            'all_places' => HangoutPlace::with('owner')->latest()->paginate(8)
        ];
    }
}; ?>

<div class="min-h-screen bg-zinc-900 text-white pb-20">
    
    <div class="max-w-7xl mx-auto space-y-8 px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="relative overflow-hidden rounded-3xl p-8 md:p-12 text-white shadow-2xl border border-red-900/30">
            <div class="absolute inset-0 bg-gradient-to-r from-red-900 via-rose-900 to-pink-900 animate-gradient-x"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold mb-2 tracking-wider text-red-200">
                        🛡️ ADMINISTRATOR MODE
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 font-syne">
                        Control Center
                    </h1>
                </div>
                
                <div class="text-right hidden md:block">
                    <p class="text-xs text-rose-300 uppercase font-bold tracking-widest">Server Status</p>
                    <div class="flex items-center gap-2 justify-end">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="font-mono font-bold text-xl">ONLINE</span>
                    </div>
                    <p class="text-xs text-rose-200 font-mono mt-1">{{ now()->format('d M Y • H:i:s') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-zinc-800/50 border border-zinc-700 p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                    <i class="fa-solid fa-users text-6xl text-blue-500"></i>
                </div>
                <p class="text-zinc-400 text-xs font-bold uppercase tracking-wider">Total Users</p>
                <p class="text-3xl font-black mt-2">{{ number_format($total_users) }}</p>
                <div class="mt-4 h-1 w-full bg-zinc-700 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 w-[70%]"></div>
                </div>
            </div>

            <div class="bg-zinc-800/50 border border-zinc-700 p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                    <i class="fa-solid fa-store text-6xl text-purple-500"></i>
                </div>
                <p class="text-zinc-400 text-xs font-bold uppercase tracking-wider">Total Bisnis</p>
                <p class="text-3xl font-black mt-2">{{ number_format($total_places) }}</p>
                <div class="mt-4 h-1 w-full bg-zinc-700 rounded-full overflow-hidden">
                    <div class="h-full bg-purple-500 w-[45%]"></div>
                </div>
            </div>

            <div class="bg-zinc-800/50 border border-zinc-700 p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                    <i class="fa-solid fa-comments text-6xl text-yellow-500"></i>
                </div>
                <p class="text-zinc-400 text-xs font-bold uppercase tracking-wider">Total Ulasan</p>
                <p class="text-3xl font-black mt-2">{{ number_format($total_reviews) }}</p>
                <div class="mt-4 h-1 w-full bg-zinc-700 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 w-[80%]"></div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-900/50 to-red-900/50 border border-orange-500/30 p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-20 group-hover:opacity-30 transition">
                    <i class="fa-solid fa-bell text-6xl text-orange-400"></i>
                </div>
                <p class="text-orange-300 text-xs font-bold uppercase tracking-wider">Perlu Verifikasi</p>
                <p class="text-3xl font-black mt-2 text-white">{{ $pending_places->count() }}</p>
                <p class="text-xs text-orange-200 mt-2">Request masuk baru</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="space-y-8">
                
                <div class="bg-zinc-800 border border-zinc-700 rounded-3xl p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-ranking-star text-yellow-500"></i> Top 5 Viral
                    </h3>
                    <div class="space-y-4">
                        @foreach($viral_places as $index => $vp)
                            <div class="flex items-center gap-3">
                                <div class="font-black text-zinc-500 text-lg w-4">{{ $index + 1 }}</div>
                                <img src="{{ $vp->image }}" class="w-10 h-10 rounded-lg object-cover bg-zinc-700">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-sm truncate">{{ $vp->name }}</h4>
                                    <p class="text-xs text-zinc-400">{{ number_format($vp->profile_views) }} Views</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-purple-400">{{ $vp->viral_score }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-zinc-800 border border-zinc-700 rounded-3xl p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-chart-bar text-blue-500"></i> Aktivitas Platform
                    </h3>
                    <div class="flex items-end justify-between h-32 gap-2">
                        @foreach([30, 45, 25, 60, 75, 50, 90] as $h)
                            <div class="w-full bg-zinc-700 rounded-t-lg hover:bg-blue-500 transition-all duration-300 relative group" style="height: {{ $h }}%">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-white text-black text-xs font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                                    {{ $h * 12 }} Hits
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-zinc-500 font-mono">
                        <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-2 space-y-8">
                
                @if($pending_places->count() > 0)
                    <div class="bg-zinc-800 border border-orange-500/30 rounded-3xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
                        <h3 class="font-bold text-lg mb-4 text-orange-400">⚠️ Menunggu Persetujuan Admin</h3>
                        
                        <div class="space-y-3">
                            @foreach($pending_places as $pending)
                                <div class="bg-zinc-900 p-4 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-zinc-700">
                                    <div class="flex items-center gap-4 w-full">
                                        <img src="{{ $pending->image }}" class="w-12 h-12 rounded-lg object-cover bg-zinc-800">
                                        <div>
                                            <h4 class="font-bold text-white">{{ $pending->name }}</h4>
                                            <p class="text-xs text-zinc-400">
                                                Owner: <span class="text-zinc-300">{{ $pending->owner->name ?? 'Anonim' }}</span> • {{ $pending->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 w-full sm:w-auto">
                                        <a href="{{ route('place.show', $pending->id) }}" target="_blank" class="flex-1 sm:flex-none px-4 py-2 text-xs font-bold bg-zinc-800 rounded-lg hover:bg-zinc-700 text-center">
                                            Cek
                                        </a>
                                        <button wire:click="verifyPlace({{ $pending->id }})" class="flex-1 sm:flex-none px-4 py-2 text-xs font-bold bg-green-600 rounded-lg hover:bg-green-500 text-white">
                                            Approve
                                        </button>
                                        <button wire:click="deletePlace({{ $pending->id }})" wire:confirm="Tolak dan hapus data ini?" class="px-3 py-2 text-xs font-bold bg-red-900/50 text-red-500 rounded-lg hover:bg-red-900">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="bg-zinc-800 border border-zinc-700 rounded-3xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-lg text-white">Database Tempat</h3>
                        
                        <div class="relative">
                            <input type="text" placeholder="Cari data..." class="bg-zinc-900 border border-zinc-700 rounded-full px-4 py-1.5 text-xs text-white focus:ring-1 focus:ring-red-500 w-40">
                        </div>
                    </div>

                    @if (session()->has('message'))
                        <div class="mb-4 p-3 bg-green-900/30 text-green-400 text-sm font-bold rounded-xl border border-green-800 flex items-center gap-2">
                            <i class="fa-solid fa-check-circle"></i> {{ session('message') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-zinc-900/50 text-zinc-400 uppercase font-bold text-[10px] tracking-wider">
                                <tr>
                                    <th class="p-3 rounded-l-lg">Bisnis</th>
                                    <th class="p-3">Statistik</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 text-center">Fitur</th>
                                    <th class="p-3 rounded-r-lg text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700">
                                @foreach($all_places as $place)
                                    <tr class="hover:bg-zinc-700/30 transition group">
                                        <td class="p-3">
                                            <div class="font-bold text-white">{{ $place->name }}</div>
                                            <div class="text-[10px] text-zinc-500">{{ $place->category }}</div>
                                        </td>
                                        <td class="p-3">
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="text-zinc-300"><i class="fa-solid fa-eye text-blue-500"></i> {{ $place->profile_views }}</span>
                                                <span class="text-zinc-300"><i class="fa-solid fa-star text-yellow-500"></i> {{ $place->avg_rating }}</span>
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            @if($place->is_verified)
                                                <span class="text-green-400 text-[10px] font-bold bg-green-900/20 px-2 py-0.5 rounded border border-green-900/30">Verified</span>
                                            @else
                                                <span class="text-orange-400 text-[10px] font-bold bg-orange-900/20 px-2 py-0.5 rounded border border-orange-900/30">Pending</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center">
                                            <button wire:click="toggleRecommendation({{ $place->id }})" 
                                                class="text-xl transition {{ $place->is_recommended ? 'text-yellow-400 scale-110' : 'text-zinc-600 hover:text-yellow-400' }}" 
                                                title="Jadikan Rekomendasi Admin">
                                                <i class="fa-solid fa-crown"></i>
                                            </button>
                                        </td>
                                        <td class="p-3 text-right">
                                            <button wire:click="deletePlace({{ $place->id }})" wire:confirm="Hapus permanen?" class="text-zinc-500 hover:text-red-500 transition p-2">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $all_places->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>