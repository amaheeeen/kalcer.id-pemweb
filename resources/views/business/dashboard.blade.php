<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-8">
        
        <div class="relative overflow-hidden rounded-3xl p-8 md:p-12 text-white">
            <div class="absolute inset-0 bg-gradient-to-r from-violet-600 via-indigo-600 to-purple-600 animate-gradient-x"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/20 text-xs font-bold mb-2 tracking-wider">
                        🚀 BUSINESS MODE
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2">
                        Hi, {{ explode(' ', auth()->user()->name)[0] }}!
                    </h1>
                    <p class="text-indigo-100 text-lg">Siap bikin bisnismu viral hari ini?</p>
                </div>
                
                @if($myPlace)
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-400 flex items-center justify-center text-black font-bold">
                            ✓
                        </div>
                        <div>
                            <p class="text-xs text-indigo-200 uppercase font-bold">Status Listing</p>
                            <p class="font-bold">{{ $myPlace->name }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if(!$myPlace)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-slate-900 border border-slate-800 p-8 rounded-3xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500 rounded-full blur-[100px] opacity-20"></div>
                
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold text-white mb-4">Claim Bisnismu Sekarang! ⚡</h2>
                    <p class="text-slate-400 mb-8 leading-relaxed">
                        Jangan biarkan pelanggan bingung. Klaim listing tempatmu agar bisa atur promo, lihat statistik, dan bikin tempatmu makin rame!
                    </p>
                    
                    <form action="{{ route('business.claim') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="relative">
                            <select name="place_id" class="w-full bg-slate-800 border-2 border-slate-700 text-white rounded-xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition hover:border-slate-600">
                                <option value="">Pilih Lokasi Bisnis...</option>
                                @foreach(\App\Models\HangoutPlace::whereNull('user_id')->orWhere('is_claimed', false)->get() as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->category }})</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-4 text-slate-400 pointer-events-none">▼</div>
                        </div>
                        <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg shadow-indigo-500/25">
                            Klaim Kepemilikan 🔥
                        </button>
                    </form>
                </div>
                
                <div class="hidden md:flex justify-center relative z-10">
                    <div class="text-[120px] leading-none animate-bounce">🏪</div>
                </div>
            </div>

        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-slate-900 border border-slate-800 p-6 rounded-3xl relative group overflow-hidden hover:border-indigo-500/50 transition duration-500">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl group-hover:bg-indigo-500/40 transition"></div>
                    
                    <div class="flex justify-between items-start mb-8">
                        <div class="p-3 bg-slate-800 rounded-xl border border-slate-700">
                            📊
                        </div>
                        <span class="text-green-400 text-xs font-bold bg-green-400/10 px-2 py-1 rounded">+12% vs last week</span>
                    </div>
                    
                    <h3 class="text-slate-400 text-sm font-bold uppercase tracking-wider mb-1">Total Profile Views</h3>
                    <p class="text-4xl font-black text-white">{{ $myPlace->profile_views }}</p>
                </div>

                <div class="bg-slate-900 border border-slate-800 p-6 rounded-3xl relative group overflow-hidden hover:border-amber-500/50 transition duration-500">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-amber-500/20 rounded-full blur-2xl group-hover:bg-amber-500/40 transition"></div>
                    
                    <div class="flex justify-between items-start mb-8">
                        <div class="p-3 bg-slate-800 rounded-xl border border-slate-700">
                            🔥
                        </div>
                        <span class="text-amber-400 text-xs font-bold bg-amber-400/10 px-2 py-1 rounded">Trending</span>
                    </div>
                    
                    <h3 class="text-slate-400 text-sm font-bold uppercase tracking-wider mb-1">Viral Score</h3>
                    <div class="flex items-baseline gap-2">
                        <p class="text-4xl font-black text-white">{{ $myPlace->viral_score }}</p>
                        <span class="text-slate-500 text-sm">/ 100</span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-pink-600 to-rose-600 p-6 rounded-3xl text-white flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-xl mb-1">Boost Traffic! 🚀</h3>
                        <p class="text-pink-100 text-sm opacity-90">Pasang promo biar anak Jaksel makin sering mampir.</p>
                    </div>
                    <button onclick="document.getElementById('promo-input').focus()" class="mt-4 bg-white text-pink-600 font-bold py-3 rounded-xl hover:bg-pink-50 transition">
                        Pasang Promo
                    </button>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 relative overflow-hidden">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50"></div>
                
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-2">📢 Live Promo Board</h3>
                        <p class="text-slate-400 text-sm">Promo yang kamu tulis di sini akan muncul dengan badge <span class="text-indigo-400 font-bold">Special Offer</span> di halaman detail.</p>
                        
                        <div class="mt-6 p-4 bg-slate-950 rounded-xl border border-slate-800 border-dashed">
                            <p class="text-xs text-slate-500 mb-2 uppercase tracking-wide">Preview di User:</p>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-700 rounded-lg animate-pulse"></div>
                                <div>
                                    <div class="h-3 w-24 bg-gray-700 rounded mb-2 animate-pulse"></div>
                                    <span class="text-xs font-bold bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded border border-indigo-500/30">
                                        {{ $myPlace->promo_text ?: 'Belum ada promo aktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 w-full">
                        <form action="{{ route('business.promo', $myPlace->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-slate-300 text-sm font-bold ml-1">Teks Promo (Max 50 Karakter)</label>
                                <div class="relative mt-2">
                                    <input id="promo-input" type="text" name="promo_text" 
                                           value="{{ $myPlace->promo_text }}"
                                           class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl p-4 pl-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                           placeholder="Contoh: Diskon 20% pake KTM!">
                                    <span class="absolute left-4 top-4 text-xl">🏷️</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                @if($myPlace->promo_text)
                                    <p class="text-green-400 text-xs flex items-center gap-1">
                                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                        Aktif s.d {{ \Carbon\Carbon::parse($myPlace->promo_expires_at)->format('H:i') }}
                                    </p>
                                @else
                                    <p class="text-slate-500 text-xs">Tidak ada promo aktif</p>
                                @endif
                                
                                <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-indigo-600/20">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>