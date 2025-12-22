<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    // Tidak ada logic khusus, cuma view statis
}; ?>

<div class="bg-white font-sans text-gray-900">
    
    <section class="relative py-20 px-4 text-center bg-gray-50 border-b border-gray-100">
        <div class="max-w-3xl mx-auto space-y-6">
            <span class="text-xs font-bold tracking-widest text-purple-600 uppercase bg-purple-100 px-3 py-1 rounded-full">
                Behind The Scene
            </span>
            <h1 class="text-4xl md:text-6xl font-black tracking-tight text-gray-900">
                Kami Adalah <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500">Kalcer.ID</span>
            </h1>
            <p class="text-lg text-gray-500 leading-relaxed">
                Sekelompok mahasiswa yang lelah dengan review palsu dan tempat nongkrong yang cuma "menang di foto". Kami membangun platform ini untuk memvalidasi *vibe* Jakarta Selatan.
            </p>
        </div>
    </section>

    <section class="py-24 max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold mb-4">Meet The Curators 🚀</h2>
            <p class="text-gray-500">Orang-orang di balik layar yang memastikan asupan tempat nongkrongmu aman.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 justify-center">
            
            <div class="group text-center space-y-4">
                <div class="relative w-32 h-32 mx-auto bg-gray-100 rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:shadow-purple-200 transition-all duration-300">
                    <img src="https://api.dicebear.com/9.x/notionists/svg?seed=Felix&backgroundColor=e0e7ff" alt="Farhan" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-lg">Farhan</h3>
                    <p class="text-xs font-bold text-purple-600 uppercase tracking-wide">The Hacker</p>
                    <p class="text-xs text-gray-400 mt-2">"Kodingan rapi, hidup happy."</p>
                </div>
            </div>

            <div class="group text-center space-y-4">
                <div class="relative w-32 h-32 mx-auto bg-gray-100 rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:shadow-blue-200 transition-all duration-300">
                    <img src="https://api.dicebear.com/9.x/notionists/svg?seed=Ryan&backgroundColor=dbeafe" alt="Alphard" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-lg">Alphard</h3>
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">System Architect</p>
                    <p class="text-xs text-gray-400 mt-2">"Server aman, hati tenang."</p>
                </div>
            </div>

            <div class="group text-center space-y-4">
                <div class="relative w-32 h-32 mx-auto bg-gray-100 rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:shadow-green-200 transition-all duration-300">
                    <img src="https://api.dicebear.com/9.x/notionists/svg?seed=Mason&backgroundColor=dcfce7" alt="Ali" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-lg">Ali</h3>
                    <p class="text-xs font-bold text-green-600 uppercase tracking-wide">UI/UX Wizard</p>
                    <p class="text-xs text-gray-400 mt-2">"Pixel perfect adalah jalan ninjaku."</p>
                </div>
            </div>

            <div class="group text-center space-y-4">
                <div class="relative w-32 h-32 mx-auto bg-gray-100 rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:shadow-yellow-200 transition-all duration-300">
                    <img src="https://api.dicebear.com/9.x/notionists/svg?seed=Leo&backgroundColor=fef9c3" alt="Irfan" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-lg">Irfan</h3>
                    <p class="text-xs font-bold text-yellow-600 uppercase tracking-wide">Database Master</p>
                    <p class="text-xs text-gray-400 mt-2">"Select * from feelings where user=me."</p>
                </div>
            </div>

            <div class="group text-center space-y-4">
                <div class="relative w-32 h-32 mx-auto bg-gray-100 rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-110 group-hover:shadow-pink-200 transition-all duration-300">
                    <img src="https://api.dicebear.com/9.x/notionists/svg?seed=Jessica&backgroundColor=fce7f3" alt="Audrey" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-lg">Audrey</h3>
                    <p class="text-xs font-bold text-pink-600 uppercase tracking-wide">Creative Lead</p>
                    <p class="text-xs text-gray-400 mt-2">"Estetik itu kebutuhan pokok."</p>
                </div>
            </div>

        </div>
    </section>

    <section class="py-20 bg-black text-white text-center">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8 opacity-80">Built with Modern Stack</h2>
            <div class="flex flex-wrap justify-center gap-6 md:gap-12 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="flex flex-col items-center gap-2 hover:opacity-100 transition">
                    <span class="font-bold text-xl">Laravel</span>
                </div>
                <div class="flex flex-col items-center gap-2 hover:opacity-100 transition">
                    <span class="font-bold text-xl">Livewire</span>
                </div>
                <div class="flex flex-col items-center gap-2 hover:opacity-100 transition">
                    <span class="font-bold text-xl">Tailwind</span>
                </div>
                <div class="flex flex-col items-center gap-2 hover:opacity-100 transition">
                    <span class="font-bold text-xl">Alpine.js</span>
                </div>
            </div>
        </div>
    </section>
</div>