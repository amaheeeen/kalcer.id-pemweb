<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\HangoutPlace;
use App\Models\Review;
use App\Models\Checkin; // [NEW] Import Model Checkin
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

new 
#[Layout('components.layouts.app')]
class extends Component {
    public HangoutPlace $place;

    // Review State
    #[Validate('required|integer|min:1|max:5')] 
    public $rating = 0; 
    #[Validate('required|string|min:3|max:500')] 
    public $content = '';

    // Bookmark State
    public $isSaved = false;

    // [NEW] Check-in State
    public $hasCheckedIn = false;

    public function getIsOpenProperty()
    {
        $now = Carbon::now()->hour;
        // Asumsi sederhana: Buka jam 10 - 22
        return $now >= 10 && $now <= 22;
    }

    public function mount(HangoutPlace $place)
    {
        $this->place = $place;

        // [NEW] Increment View Counter
        $this->place->increment('profile_views');

        if (Auth::check()) {
            // Cek Bookmark
            $this->isSaved = Auth::user()->bookmarks()->where('hangout_place_id', $place->id)->exists();

            // [NEW] Cek Status Check-in (Valid 3 Jam Terakhir)
            $this->hasCheckedIn = Checkin::where('user_id', Auth::id())
                ->where('hangout_place_id', $place->id)
                ->where('created_at', '>=', now()->subHours(3))
                ->exists();
        }
    }

    // [NEW] Logic Check-in Real-time
    public function checkIn()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if ($this->hasCheckedIn) {
            // Fitur Check-out (Opsional: Hapus data checkin terakhir)
            Checkin::where('user_id', Auth::id())
                ->where('hangout_place_id', $this->place->id)
                ->latest()
                ->first()
                ?->delete();
                
            $this->hasCheckedIn = false;
        } else {
            // Create Check-in Baru
            Checkin::create([
                'user_id' => Auth::id(),
                'hangout_place_id' => $this->place->id
            ]);
            
            $this->hasCheckedIn = true;
            session()->flash('checkin_status', 'Berhasil Check-in! Terima kasih infonya 🙌');
        }
        
        // Refresh data model untuk update crowd_status di UI
        $this->place->refresh();
    }

    public function toggleBookmark()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        Auth::user()->bookmarks()->toggle($this->place->id);
        
        $this->isSaved = !$this->isSaved;
        $msg = $this->isSaved ? 'Disimpan ke wishlist! 📌' : 'Dihapus dari wishlist.';
        session()->flash('bookmark_status', $msg);
    }

    public function submitReview()
    {
        if (!Auth::check()) return $this->redirect(route('login'), navigate: true);
        
        $this->validate();

        Review::create([
            'user_id' => Auth::id(),
            'hangout_place_id' => $this->place->id,
            'rating' => $this->rating,
            'content' => $this->content
        ]);

        // Naikkan skor viral
        $this->place->increment('viral_score', 5); // Naik 5 poin biar cepat viral untuk testing
        
        // LOGIKA NOTIFIKASI
        // Kirim notifikasi ke user lain jika skor viral tembus > 80
        if ($this->place->viral_score >= 80) {
            $users = User::where('id', '!=', Auth::id())->inRandomOrder()->take(5)->get();
            
            if ($users->count() > 0) {
                Notification::send($users, new ViralAlert($this->place));
            }
        }

        $this->reset(['rating', 'content']);
        session()->flash('message', 'Review dikirim! Viral score naik 🔥');
    }

    // --- FITUR KLAIM BISNIS ---
    public function claimBusiness()
    {
        if (!Auth::check()) return $this->redirect(route('login'), navigate: true);

        if ($this->place->user_id) {
            session()->flash('claim_error', 'Tempat ini sudah diklaim oleh pemilik lain.');
            return;
        }

        if (Auth::user()->business) {
            session()->flash('claim_error', 'Anda sudah mengelola bisnis lain. Satu akun hanya boleh mengklaim satu tempat.');
            return;
        }

        $this->place->update(['user_id' => Auth::id()]);
        Auth::user()->update(['role' => 'business_owner']);
        return $this->redirect(route('business.index'), navigate: true);
    }
}; ?>

<div class="min-h-screen bg-white dark:bg-zinc-900 pb-20">
    
    {{-- HERO SECTION --}}
    <div class="relative h-[50vh] md:h-[60vh] w-full overflow-hidden group">
        <img src="{{ $place->image }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105 filter brightness-75">
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/40 to-transparent"></div>

        <a href="{{ route('explore') }}" wire:navigate class="absolute top-6 left-6 z-20 w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10 z-20">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md border border-white/20 rounded-full text-xs font-bold text-white uppercase tracking-wider">
                        {{ $place->category }}
                    </span>
                    <span class="px-3 py-1 bg-yellow-500 rounded-full text-xs font-bold text-black uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-star"></i> {{ $place->avg_rating }} / 5.0
                    </span>
                </div>

                <h1 class="text-4xl md:text-6xl font-black font-syne text-white leading-tight mb-2 drop-shadow-lg">
                    {{ $place->name }}
                </h1>
                
                <p class="text-zinc-300 text-sm md:text-base flex items-center gap-2 max-w-2xl">
                    <i class="fa-solid fa-location-dot text-indigo-400"></i>
                    {{ $place->address }}
                </p>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-30">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI (Konten Utama) --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- STATS BAR --}}
                <div class="bg-white dark:bg-zinc-800 rounded-3xl p-6 shadow-xl border border-zinc-100 dark:border-zinc-700 flex justify-around items-center text-center">
                    <div>
                        <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Status</p>
                        @if($this->isOpen)
                            <span class="text-green-500 font-bold text-sm flex items-center gap-1 justify-center"><span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> BUKA</span>
                        @else
                            <span class="text-red-500 font-bold text-sm">TUTUP</span>
                        @endif
                    </div>
                    <div class="w-px h-10 bg-zinc-200 dark:bg-zinc-700"></div>
                    <div>
                        <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Crowd</p>
                        <span class="text-zinc-900 dark:text-white font-bold text-sm">{{ ucfirst($place->crowd_status) }}</span>
                    </div>
                    <div class="w-px h-10 bg-zinc-200 dark:bg-zinc-700"></div>
                    <div>
                        <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Views</p>
                        <span class="text-indigo-500 font-bold text-sm">{{ number_format($place->profile_views) }}</span>
                    </div>
                </div>

                {{-- [NEW] LIVE CROWD CHECK-IN SECTION --}}
                <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-3xl p-5 border border-zinc-200 dark:border-zinc-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="relative flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $place->crowd_status === 'Sepi' ? 'bg-green-400' : ($place->crowd_status === 'Ramai' ? 'bg-orange-400' : 'bg-red-400') }}"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 {{ $place->crowd_status === 'Sepi' ? 'bg-green-500' : ($place->crowd_status === 'Ramai' ? 'bg-orange-500' : 'bg-red-500') }}"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-zinc-900 dark:text-white text-sm">Live Crowd Monitor</h4>
                            <p class="text-xs text-zinc-500">
                                {{ $place->checkins()->where('created_at', '>=', now()->subHours(3))->count() }} orang sedang disini
                            </p>
                        </div>
                    </div>
                    
                    <button wire:click="checkIn" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-sm transition-all transform active:scale-95 shadow-md flex items-center justify-center gap-2 {{ $hasCheckedIn ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 hover:opacity-90' }}">
                        @if($hasCheckedIn)
                            <i class="fa-solid fa-person-walking-arrow-right"></i> Check-out
                        @else
                            <i class="fa-solid fa-location-dot"></i> Saya Disini!
                        @endif
                    </button>
                </div>

                @if (session()->has('checkin_status'))
                    <div class="p-3 bg-green-500/10 border border-green-500/20 text-green-500 text-xs font-bold rounded-xl text-center animate-pulse">
                        {{ session('checkin_status') }}
                    </div>
                @endif
                {{-- END LIVE CROWD SECTION --}}

                @if($place->promo_text && \Carbon\Carbon::parse($place->promo_expires_at)->isFuture())
                    <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/20">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="relative z-10 flex items-start gap-4">
                            <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                                <i class="fa-solid fa-tags text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-1">Special Offer! 🔥</h3>
                                <p class="text-indigo-100 text-sm leading-relaxed mb-3">
                                    {{ $place->promo_text }}
                                </p>
                                <div class="text-xs font-mono bg-black/20 px-3 py-1 rounded inline-block">
                                    Berlaku sampai {{ \Carbon\Carbon::parse($place->promo_expires_at)->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="prose dark:prose-invert max-w-none">
                    <h3 class="text-xl font-bold font-syne mb-3 text-zinc-900 dark:text-white">Tentang Tempat Ini</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{ $place->description }}
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-bold font-syne mb-4 text-zinc-900 dark:text-white">Fasilitas</h3>
                    <div class="flex flex-wrap gap-3">
                        @if(is_array($place->facilities))
                            @foreach($place->facilities as $facility)
                                <div class="px-4 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-sm font-medium flex items-center gap-2 border border-zinc-200 dark:border-zinc-700">
                                    <i class="fa-solid fa-check text-indigo-500"></i> {{ $facility }}
                                </div>
                            @endforeach
                        @else
                            <p class="text-zinc-500 italic text-sm">Tidak ada data fasilitas.</p>
                        @endif
                    </div>
                </div>

                <div class="pt-8 border-t border-zinc-200 dark:border-zinc-800" id="reviews">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold font-syne text-zinc-900 dark:text-white">
                            Kata Netizen ({{ $place->reviews->count() }})
                        </h3>
                    </div>

                    @if (session()->has('message'))
                        <div class="mb-4 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-xl text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-check-circle"></i> {{ session('message') }}
                        </div>
                    @endif
                    
                    @auth
                        <div class="bg-zinc-50 dark:bg-zinc-800/50 p-6 rounded-3xl mb-8 border border-zinc-200 dark:border-zinc-700">
                            <h4 class="font-bold text-zinc-900 dark:text-white mb-4">Gimana pengalamanmu?</h4>
                            <form wire:submit="submitReview">
                                <div class="flex gap-2 mb-4">
                                    @for($i=1; $i<=5; $i++)
                                        <button type="button" wire:click="$set('rating', {{ $i }})" class="text-2xl transition hover:scale-110 {{ $rating >= $i ? 'text-yellow-400' : 'text-zinc-300 dark:text-zinc-600' }}">
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                    @endfor
                                    @error('rating') <span class="text-red-500 text-xs ml-2 mt-2">{{ $message }}</span> @enderror
                                </div>
                                <textarea wire:model="content" placeholder="Ceritain dong, makanannya enak? WiFi kenceng?..." class="w-full bg-white dark:bg-zinc-900 border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white placeholder-zinc-400 mb-2 h-24 resize-none"></textarea>
                                @error('content') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-6 rounded-xl text-sm transition shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim Review
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-3xl mb-8 border border-indigo-100 dark:border-indigo-500/20 text-center">
                            <p class="text-indigo-800 dark:text-indigo-300 font-bold mb-2">Mau kasih review?</p>
                            <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-xl text-sm font-bold">Login Sekarang</a>
                        </div>
                    @endauth

                    <div class="space-y-6">
                        @forelse($place->reviews as $review)
                            <div class="flex gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center font-bold text-zinc-500 dark:text-zinc-400">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h5 class="font-bold text-zinc-900 dark:text-white text-sm">{{ $review->user->name }}</h5>
                                        <span class="text-xs text-zinc-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex text-yellow-400 text-xs mb-2">
                                        @for($j=1; $j<=5; $j++)
                                            <i class="{{ $j <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="text-zinc-600 dark:text-zinc-300 text-sm leading-relaxed bg-zinc-50 dark:bg-zinc-800/50 p-3 rounded-xl rounded-tl-none">
                                        {{ $review->content }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-zinc-400 italic text-sm">Belum ada review. Jadilah yang pertama!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (Sidebar) --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-4">
                    
                    <div class="rounded-3xl overflow-hidden h-48 relative border border-zinc-200 dark:border-zinc-700 group">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 
                            style="filter: grayscale(100%) invert(92%) contrast(83%); border:0;"
                            src="https://maps.google.com/maps?q={{ $place->latitude }},{{ $place->longitude }}&hl=id&z=15&output=embed">
                        </iframe>

                        <div class="absolute inset-0 flex items-center justify-center bg-black/10 group-hover:bg-transparent transition duration-300 pointer-events-none">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $place->latitude }},{{ $place->longitude }}" 
                            target="_blank" 
                            class="pointer-events-auto px-5 py-2.5 bg-white text-zinc-900 font-bold text-xs rounded-full shadow-xl hover:scale-105 hover:bg-zinc-100 transition flex items-center gap-2 transform translate-y-2 group-hover:translate-y-0 opacity-90 group-hover:opacity-100">
                                <i class="fa-solid fa-map-location-dot text-indigo-600"></i> Buka di Google Maps
                            </a>
                        </div>
                    </div>
                    
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $place->latitude }},{{ $place->longitude }}" target="_blank" class="block w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-center rounded-2xl shadow-lg shadow-indigo-500/20 transition transform hover:-translate-y-1">
                        <i class="fa-solid fa-location-arrow mr-2"></i> Petunjuk Arah
                    </a>

                    <div class="grid grid-cols-2 gap-4">
                        <button wire:click="toggleBookmark" class="py-3 bg-white dark:bg-zinc-800 border {{ $isSaved ? 'border-indigo-500 text-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200' }} font-bold rounded-2xl hover:bg-zinc-50 dark:hover:bg-zinc-700 transition flex items-center justify-center gap-2">
                            <i class="{{ $isSaved ? 'fa-solid' : 'fa-regular' }} fa-bookmark"></i> 
                            {{ $isSaved ? 'Disimpan' : 'Simpan' }}
                        </button>

                        <button class="py-3 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-bold rounded-2xl hover:bg-zinc-50 dark:hover:bg-zinc-700 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-share-nodes"></i> Share
                        </button>
                    </div>

                    @if (session()->has('bookmark_status'))
                        <div class="text-center text-xs font-bold text-indigo-500 animate-pulse">
                            {{ session('bookmark_status') }}
                        </div>
                    @endif

                    <div class="bg-zinc-50 dark:bg-zinc-800/50 p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700">
                        <h4 class="font-bold text-sm text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
                            <i class="fa-regular fa-clock"></i> Jam Operasional
                        </h4>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-zinc-500">Senin - Minggu</span>
                            <span class="text-zinc-800 dark:text-zinc-300 font-medium">
                                {{ $place->operational_hours ?? '10:00 - 22:00' }}
                            </span>
                        </div>
                    </div>

                    @auth
                        @if (session()->has('claim_error'))
                            <div class="p-3 bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-bold rounded-xl text-center">
                                {{ session('claim_error') }}
                            </div>
                        @endif

                        @if(!$place->user_id)
                            <div class="mt-4 p-4 bg-gradient-to-r from-zinc-900 to-zinc-800 dark:from-zinc-800 dark:to-zinc-700 rounded-2xl border border-zinc-700 text-center relative overflow-hidden group">
                                <div class="absolute inset-0 bg-indigo-600/20 group-hover:bg-indigo-600/30 transition"></div>
                                <i class="fa-solid fa-briefcase text-2xl text-white mb-2 relative z-10"></i>
                                <h4 class="font-bold text-white relative z-10 text-sm">Ini bisnis Anda?</h4>
                                <p class="text-zinc-400 text-xs mb-3 relative z-10">Klaim sekarang untuk kelola info & pasang promo.</p>
                                
                                <button wire:click="claimBusiness" 
                                        wire:confirm="Apakah Anda yakin ingin mengklaim tempat ini sebagai bisnis Anda?"
                                        class="relative z-10 w-full py-2 bg-white text-zinc-900 font-bold text-xs rounded-lg hover:bg-zinc-200 transition">
                                    Klaim Bisnis Ini
                                </button>
                            </div>
                        @elseif($place->user_id === Auth::id())
                            <div class="mt-4">
                                <a href="{{ route('business.index') }}" class="block w-full py-3 bg-zinc-900 dark:bg-white text-white dark:text-black font-bold text-center rounded-2xl shadow-lg transition hover:scale-105">
                                    <i class="fa-solid fa-gauge-high mr-2"></i> Dashboard Bisnis
                                </a>
                            </div>
                        @endif
                    @endauth

                </div>
            </div>

        </div>
    </div>
</div>