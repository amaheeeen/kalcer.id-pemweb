<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-white">
        
        <div class="flex justify-between items-end mb-8 border-b border-slate-700 pb-4">
            <div>
                <h1 class="text-3xl font-bold">Business Dashboard</h1>
                <p class="text-slate-400 mt-1">Kelola performa tempat hangout Anda.</p>
            </div>
            <span class="px-3 py-1 rounded bg-amber-500/10 text-amber-500 text-xs font-bold border border-amber-500/20">
                PRO ACCOUNT
            </span>
        </div>

        @if(!$myPlace)
            <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700 text-center">
                <div class="text-5xl mb-4">🏪</div>
                <h2 class="text-2xl font-bold mb-2">Anda belum mengelola tempat</h2>
                <p class="text-slate-400 mb-6">Cari tempat hangout Anda di database kami dan klaim kepemilikannya.</p>
                
                <form action="{{ route('business.claim') }}" method="POST" class="max-w-md mx-auto flex gap-2">
                    @csrf
                    <select name="place_id" class="flex-1 bg-slate-900 border border-slate-600 text-white rounded-lg p-3">
                        <option value="">Pilih Tempat Anda...</option>
                        @foreach(\App\Models\HangoutPlace::whereNull('user_id')->orWhere('is_claimed', false)->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->category }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-3 px-6 rounded-lg transition">
                        Klaim
                    </button>
                </form>
            </div>

        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-slate-700/50 text-8xl group-hover:scale-110 transition">📊</div>
                    <h3 class="text-slate-400 text-sm font-medium uppercase tracking-wider mb-2">Total Kunjungan Profil</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold">{{ $myPlace->profile_views }}</span>
                        <span class="text-sm text-green-400">▲ Views</span>
                    </div>
                </div>

                <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700">
                    <h3 class="text-slate-400 text-sm font-medium uppercase tracking-wider mb-2">Status Listing</h3>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-xl font-bold">Terverifikasi ✅</span>
                    </div>
                    <p class="text-slate-500 text-sm mt-2">{{ $myPlace->name }}</p>
                </div>

                <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700">
                    <h3 class="text-slate-400 text-sm font-medium uppercase tracking-wider mb-2">Viral Score</h3>
                    <span class="text-4xl font-bold text-amber-400">{{ $myPlace->viral_score }}</span>
                    <span class="text-slate-500 text-sm">/ 100</span>
                </div>
            </div>

            <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-700 bg-slate-800/50">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        📢 Update Promo Real-time
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('business.promo', $myPlace->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-slate-300 text-sm mb-2">Teks Promo</label>
                            <input type="text" name="promo_text" 
                                   value="{{ $myPlace->promo_text }}"
                                   class="w-full bg-slate-900 border border-slate-600 rounded-lg p-3 text-white focus:border-amber-500" 
                                   placeholder="Contoh: Diskon 20% All Items!">
                        </div>
                        <div class="flex justify-between items-center">
                            @if($myPlace->promo_text)
                                <span class="text-green-400 text-sm">✅ Promo Aktif</span>
                            @else
                                <span class="text-slate-500 text-sm">Tidak ada promo</span>
                            @endif
                            <button class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-2 px-6 rounded-lg transition">
                                Pasang Promo 🚀
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>