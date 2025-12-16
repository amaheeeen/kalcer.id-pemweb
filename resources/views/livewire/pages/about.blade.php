<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    
    #[Validate('required|min:3')] 
    public $name = '';

    #[Validate('required|email')] 
    public $email = '';

    #[Validate('required|min:10')] 
    public $message = '';

    public $sent = false;

    public function submit()
    {
        $this->validate();

        // Simulasi kirim email (bisa diganti logic kirim beneran nanti)
        sleep(1); 

        $this->sent = true;
        $this->reset(['name', 'email', 'message']);
    }
}; ?>

<div class="bg-white min-h-screen font-sans text-gray-900 pb-20">
    
    <section class="relative py-20 overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-purple-100 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-pink-100 rounded-full blur-3xl opacity-50"></div>

        <div class="relative max-w-4xl mx-auto px-4 text-center space-y-8">
            <span class="inline-block py-1 px-3 rounded-full bg-gray-900 text-white text-xs font-bold tracking-widest uppercase mb-4">
                The Backstory
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight">
                Kami lelah dengan <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">Rekomendasi Zonk.</span>
            </h1>
            <p class="text-xl text-gray-500 leading-relaxed max-w-2xl mx-auto">
                Kalcer.ID lahir dari keresahan kolektif: scrolling TikTok berjam-jam cari tempat WFC, tapi pas didatengin ternyata WiFi lemot, kopi <i>watery</i>, dan cuma bagus di kamera doang.
            </p>
            <p class="text-lg font-medium text-gray-900">
                Kami hadir buat nge-filter <b>noise</b> Kalau ada di sini, berarti valid.
            </p>
        </div>
    </section>

    <section class="py-12 bg-gray-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-6 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">No Gatekeeping</h3>
                    <p class="text-gray-500 leading-relaxed">Hidden gem seharusnya dinikmati bareng, bukan disembunyiin biar kelihatan *edgy* sendiri.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center mb-6 text-pink-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Data-Driven Curation</h3>
                    <p class="text-gray-500 leading-relaxed">Viral doang nggak cukup. Kami cek *crowd level*, fasilitas, dan *real reviews* sebelum kasih badge.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-6 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Community First</h3>
                    <p class="text-gray-500 leading-relaxed">Platform ini hidup dari kontribusi lo. Review jujur lo menyelamatkan hari Minggu orang lain.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-extrabold mb-12">The Curators</h2>
        <div class="flex flex-wrap justify-center gap-12">
            <div class="group">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:border-purple-200 transition duration-300">
                    <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                </div>
                <h4 class="mt-4 font-bold text-lg">Kai</h4>
                <p class="text-sm text-purple-600 font-medium">Chief Curator</p>
                <p class="text-xs text-gray-400 mt-1">Coffee Snob</p>
            </div>
            <div class="group">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:border-purple-200 transition duration-300">
                    <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                </div>
                <h4 class="mt-4 font-bold text-lg">Kai</h4>
                <p class="text-sm text-purple-600 font-medium">Chief Curator</p>
                <p class="text-xs text-gray-400 mt-1">Coffee Snob</p>
            </div>
            <div class="group">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:border-purple-200 transition duration-300">
                    <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                </div>
                <h4 class="mt-4 font-bold text-lg">Kai</h4>
                <p class="text-sm text-purple-600 font-medium">Chief Curator</p>
                <p class="text-xs text-gray-400 mt-1">Coffee Snob</p>
            </div>
            <div class="group">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:border-pink-200 transition duration-300">
                    <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                </div>
                <h4 class="mt-4 font-bold text-lg">Sasha</h4>
                <p class="text-sm text-pink-600 font-medium">Trend Analyst</p>
                <p class="text-xs text-gray-400 mt-1">TikTok Addict</p>
            </div>
            <div class="group">
                <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:border-yellow-200 transition duration-300">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                </div>
                <h4 class="mt-4 font-bold text-lg">Ray</h4>
                <p class="text-sm text-yellow-600 font-medium">Tech Lead</p>
                <p class="text-xs text-gray-400 mt-1">Maps Enthusiast</p>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="bg-black rounded-3xl text-white overflow-hidden shadow-2xl flex flex-col md:flex-row">
            
            <div class="p-10 md:p-16 md:w-5/12 bg-gradient-to-br from-gray-900 to-black relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-purple-900 rounded-full blur-3xl opacity-20 -mr-10 -mt-10"></div>
                
                <h2 class="text-3xl font-extrabold mb-6">Hit Us Up! 🤙</h2>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    Punya rekomendasi *hidden gem* yang belum masuk radar? Atau mau collab? Gas langsung isi form atau mampir ke HQ.
                </p>

                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span>collab@kalcer.id</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span>Senopati, Jaksel (Ofc)</span>
                    </div>
                </div>

                <div class="mt-12 flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-white transition">Instagram</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">TikTok</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Twitter</a>
                </div>
            </div>

            <div class="p-10 md:p-16 md:w-7/12 bg-white text-gray-900">
                @if($sent)
                    <div class="h-full flex flex-col items-center justify-center text-center animate-pulse">
                        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Pesan Terkirim!</h3>
                        <p class="text-gray-500">Thanks udah reach out. Tim kami bakal bales secepatnya.</p>
                        <button wire:click="$set('sent', false)" class="mt-6 text-sm font-bold text-purple-600 hover:underline">Kirim pesan lagi</button>
                    </div>
                @else
                    <form wire:submit="submit" class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lo</label>
                            <input wire:model="name" type="text" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-black focus:border-transparent transition" placeholder="Siapa nih?">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-black focus:border-transparent transition" placeholder="email@lo.com">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pesan / Rekomendasi</label>
                            <textarea wire:model="message" rows="4" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-black focus:border-transparent transition" placeholder="Spill tempat hidden gem atau cuma mau say hi..."></textarea>
                            @error('message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 rounded-lg bg-black text-white font-bold hover:bg-gray-800 transition transform hover:-translate-y-0.5 shadow-lg flex justify-center items-center gap-2">
                            <span wire:loading.remove>Kirim Pesan 🚀</span>
                            <span wire:loading class="animate-spin">⏳</span> Sending...
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>