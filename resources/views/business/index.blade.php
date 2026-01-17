<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-8 p-4">
        
        <div class="relative overflow-hidden rounded-3xl p-8 md:p-12 text-white shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-violet-600 via-indigo-600 to-purple-600 animate-gradient-x"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/20 text-xs font-bold mb-2 tracking-wider">
                        🚀 BUSINESS OWNER MODE
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2 font-syne">
                        Hi, {{ explode(' ', auth()->user()->name)[0] }}!
                    </h1>
                    <p class="text-indigo-100 text-lg">Siap bikin bisnismu viral hari ini?</p>
                </div>
                
                @if($myPlace)
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl flex items-center gap-3 hover:bg-white/20 transition cursor-default">
                        <div class="w-10 h-10 rounded-full bg-green-400 flex items-center justify-center text-black font-bold shadow-lg shadow-green-400/30">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-200 uppercase font-bold tracking-wider">Status Listing</p>
                            <p class="font-bold text-lg leading-tight">{{ $myPlace->name }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if(!$myPlace)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start bg-zinc-900 border border-zinc-800 p-8 rounded-3xl relative overflow-hidden shadow-xl" x-data="{ mode: 'search' }">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500 rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
                
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold text-white mb-2 font-syne">Claim Bisnismu Sekarang! ⚡</h2>
                    
                    <div class="flex gap-6 mb-6 border-b border-zinc-700">
                        <button @click="mode = 'search'" 
                            :class="mode === 'search' ? 'text-indigo-400 border-b-2 border-indigo-400' : 'text-zinc-500 hover:text-zinc-300'"
                            class="pb-2 font-bold transition flex items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass"></i> Cari di Database
                        </button>
                        <button @click="mode = 'manual'" 
                            :class="mode === 'manual' ? 'text-indigo-400 border-b-2 border-indigo-400' : 'text-zinc-500 hover:text-zinc-300'"
                            class="pb-2 font-bold transition flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Input Manual
                        </button>
                    </div>
                    
                    <form action="{{ route('business.claim') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div x-show="mode === 'search'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <p class="text-zinc-400 mb-4 text-sm">Cari nama tempatmu yang sudah terdaftar di sistem kami.</p>
                            <div class="relative group">
                                <select name="place_id" class="w-full bg-zinc-800 border-2 border-zinc-700 text-white rounded-xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition hover:border-zinc-600 cursor-pointer">
                                    <option value="">-- Pilih Lokasi Bisnis --</option>
                                    @foreach($availablePlaces as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->category }})</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-4 text-zinc-400 pointer-events-none group-hover:text-white transition">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <div x-show="mode === 'manual'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                            <p class="text-zinc-400 mb-2 text-sm">Tempatmu belum ada? Tambahkan baru sekarang.</p>
                            
                            <div>
                                <label class="text-xs font-bold text-zinc-500 mb-1 block uppercase">Nama Bisnis</label>
                                <input type="text" name="new_name" placeholder="Contoh: Kopi Senja Jaksel" 
                                    class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>
                            
                            <div>
                                <label class="text-xs font-bold text-zinc-500 mb-1 block uppercase">Kategori</label>
                                <div class="relative">
                                    <select name="new_category" class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 appearance-none">
                                        <option value="Coffee Shop">Coffee Shop</option>
                                        <option value="Culinary">Culinary / Resto</option>
                                        <option value="Public Park">Public Park</option>
                                        <option value="Creative Space">Creative Space</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-zinc-500 pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-zinc-500 mb-1 block uppercase">Alamat Singkat</label>
                                <input type="text" name="new_address" placeholder="Contoh: Jl. Cipete Raya No. 10" 
                                    class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg shadow-indigo-600/25 flex items-center justify-center gap-2 group">
                            <i class="fa-solid fa-rocket group-hover:-translate-y-1 transition"></i> 
                            <span x-text="mode === 'search' ? 'Klaim Bisnis Ini' : 'Tambahkan & Klaim'"></span>
                        </button>
                    </form>
                </div>
                
                <div class="hidden md:flex flex-col items-center justify-center relative z-10 h-full text-center">
                    <div class="text-[120px] leading-none animate-bounce drop-shadow-2xl mb-4">🏪</div>
                    <h3 class="text-xl font-bold text-white mb-2">Kenapa harus gabung?</h3>
                    <ul class="text-zinc-400 text-sm space-y-2 text-left bg-zinc-800/50 p-6 rounded-2xl border border-zinc-700">
                        <li>✅ <span class="text-white">Badge Official</span> di halaman explore</li>
                        <li>✅ Akses ke <span class="text-white">Analitik Pengunjung</span></li>
                        <li>✅ Pasang <span class="text-white">Promo Spesial</span> sesukamu</li>
                    </ul>
                </div>
            </div>

        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-zinc-900 border border-zinc-800 p-6 rounded-3xl relative group overflow-hidden hover:border-indigo-500/50 transition duration-500 shadow-lg">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl group-hover:bg-indigo-500/40 transition"></div>
                    
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 flex items-center justify-center bg-zinc-800 rounded-xl border border-zinc-700 text-2xl">
                            📊
                        </div>
                        <span class="text-green-400 text-xs font-bold bg-green-400/10 px-2 py-1 rounded border border-green-400/20">+12% vs last week</span>
                    </div>
                    
                    <h3 class="text-zinc-400 text-xs font-bold uppercase tracking-wider mb-1">Total Profile Views</h3>
                    <p class="text-4xl font-black text-white font-syne">{{ number_format($myPlace->profile_views) }}</p>
                </div>

                <div class="bg-zinc-900 border border-zinc-800 p-6 rounded-3xl relative group overflow-hidden hover:border-amber-500/50 transition duration-500 shadow-lg">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-amber-500/20 rounded-full blur-2xl group-hover:bg-amber-500/40 transition"></div>
                    
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 flex items-center justify-center bg-zinc-800 rounded-xl border border-zinc-700 text-2xl">
                            🔥
                        </div>
                        <span class="text-amber-400 text-xs font-bold bg-amber-400/10 px-2 py-1 rounded border border-amber-400/20">Trending Now</span>
                    </div>
                    
                    <h3 class="text-zinc-400 text-xs font-bold uppercase tracking-wider mb-1">Viral Score</h3>
                    <div class="flex items-baseline gap-2">
                        <p class="text-4xl font-black text-white font-syne">{{ $myPlace->viral_score }}</p>
                        <span class="text-zinc-500 text-sm font-bold">/ 100</span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-pink-600 to-rose-600 p-6 rounded-3xl text-white flex flex-col justify-between shadow-lg shadow-pink-600/20 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
                    <div class="relative z-10">
                        <h3 class="font-bold text-2xl mb-1 font-syne">Boost Traffic! 🚀</h3>
                        <p class="text-pink-100 text-sm opacity-90 leading-relaxed">Pasang promo eksklusif biar anak Jaksel makin sering mampir ke tempatmu.</p>
                    </div>
                    <button onclick="document.getElementById('promo-input').focus()" class="mt-4 bg-white text-pink-600 font-bold py-3 rounded-xl hover:bg-pink-50 transition shadow-lg flex items-center justify-center gap-2 relative z-10 group-hover:scale-105">
                        <i class="fa-solid fa-bullhorn"></i> Pasang Promo
                    </button>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-8 relative overflow-hidden shadow-xl">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50"></div>
                
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-2 font-syne flex items-center gap-2">
                            📢 Live Promo Board
                        </h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Promo yang kamu tulis di sini akan muncul dengan badge <span class="text-indigo-400 font-bold bg-indigo-400/10 px-1 rounded">Special Offer</span> di halaman detail tempatmu. Gunakan bahasa yang *catchy*!
                        </p>
                        
                        <div class="mt-6 p-4 bg-black/50 rounded-xl border border-zinc-800 border-dashed relative group">
                            <div class="absolute -top-2 left-4 bg-zinc-800 text-zinc-400 text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">User Preview</div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="w-12 h-12 bg-zinc-800 rounded-lg flex items-center justify-center text-2xl">🏷️</div>
                                <div>
                                    <div class="h-3 w-24 bg-zinc-800 rounded mb-2"></div>
                                    <span class="text-xs font-bold bg-indigo-500/20 text-indigo-300 px-2 py-1 rounded border border-indigo-500/30 inline-block">
                                        {{ $myPlace->promo_text ?: 'Belum ada promo aktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 w-full bg-zinc-950/50 p-6 rounded-2xl border border-zinc-800/50">
                        <form action="{{ route('business.promo', $myPlace->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-zinc-300 text-sm font-bold ml-1 flex justify-between">
                                    <span>Teks Promo</span>
                                    <span class="text-zinc-500 text-xs font-normal">Max 50 Karakter</span>
                                </label>
                                <div class="relative mt-2 group">
                                    <input id="promo-input" type="text" name="promo_text" 
                                           value="{{ $myPlace->promo_text }}"
                                           maxlength="50"
                                           class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl p-4 pl-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-inner placeholder-zinc-600" 
                                           placeholder="Contoh: Diskon 20% pake KTM!">
                                    <span class="absolute left-4 top-4 text-zinc-500 group-focus-within:text-indigo-500 transition">
                                        <i class="fa-solid fa-tag"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-2">
                                @if($myPlace->promo_text)
                                    <div class="text-green-400 text-xs flex items-center gap-2 bg-green-400/10 px-3 py-1.5 rounded-lg border border-green-400/20">
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                        Aktif s.d {{ \Carbon\Carbon::parse($myPlace->promo_expires_at)->format('H:i') }}
                                    </div>
                                @else
                                    <p class="text-zinc-500 text-xs bg-zinc-800 px-3 py-1.5 rounded-lg">Tidak ada promo aktif</p>
                                @endif
                                
                                <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-lg shadow-indigo-600/20 flex items-center gap-2 text-sm">
                                    <i class="fa-solid fa-rotate"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>