<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HangoutPlace;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    // =========================================================================
    // BAGIAN 1: FITUR PEMILIK USAHA (BUSINESS OWNER)
    // =========================================================================

    /**
     * Menampilkan halaman kelola bisnis milik user yang sedang login.
     */
    public function index()
    {
        // Ambil tempat milik user yang sedang login (user_id)
        $myPlace = HangoutPlace::where('user_id', Auth::id())->first();
        
        // Pastikan nama file view-nya sesuai, misal: resources/views/business/index.blade.php
        // atau tetap 'business.dashboard' jika itu view milik owner.
        return view('business.index', compact('myPlace'));
    }

    /**
     * Logika untuk mengklaim bisnis.
     */
    public function claim(Request $request)
    {
        $request->validate(['place_id' => 'required|exists:hangout_places,id']);

        $place = HangoutPlace::findOrFail($request->place_id);

        if ($place->is_claimed) {
            return back()->with('error', 'Tempat ini sudah diklaim orang lain!');
        }

        $place->update([
            'user_id' => Auth::id(), // Set user yang login sebagai pemilik
            'is_claimed' => true
        ]);

        return back()->with('success', 'Selamat! Bisnis berhasil diklaim.');
    }

    /**
     * Logika untuk update promo.
     */
    public function updatePromo(Request $request, $id)
    {
        // Pastikan yang update adalah pemilik asli (user_id)
        $place = HangoutPlace::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'promo_text' => 'required|string|max:100',
        ]);

        $place->update([
            'promo_text' => $request->promo_text,
            'promo_expires_at' => now()->addHours(24) // Promo berlaku 24 jam
        ]);

        return back()->with('success', 'Promo berhasil diupdate!');
    }

    // =========================================================================
    // BAGIAN 2: FITUR ADMINISTRATOR (DASHBOARD ANALITIK)
    // =========================================================================

    /**
     * Menampilkan Dashboard Admin dengan Data Dummy & Chart.
     */
    public function adminDashboard()
    {
        // Data Dummy untuk Dashboard Admin
        $metrics = [
            'traffic' => '24.5k',
            'viral_spots' => 8,
            'weather' => [
                'temp' => 28,
                'condition' => 'Berawan',
                'rain_chance' => '20% (19:00)'
            ],
            'most_wanted' => [
                'name' => 'LUCY IN THE SKY',
                'waitlist' => '~45 Menit'
            ]
        ];

        $chartData = [
            'labels' => ['18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '00:00'],
            'data' => [30, 55, 70, 95, 85, 60, 40]
        ];

        $updates = [
            ['category' => 'Guide', 'title' => '5 Hidden Gem Blok M', 'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1000&auto=format&fit=crop'],
            ['category' => 'News', 'title' => 'Kurasu Buka Cabang Baru?', 'image' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=1000&auto=format&fit=crop'],
            ['category' => 'Lifestyle', 'title' => 'Outfit Guide: Jaksel Vibes', 'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1000&auto=format&fit=crop']
        ];

        // PENTING: Arahkan ke file view khusus Admin agar tidak bentrok dengan view Owner
        // Saya asumsikan nama file blade barunya adalah 'resources/views/business/admin_dashboard.blade.php'
        return view('business.admin_dashboard', compact('metrics', 'chartData', 'updates'));
    }
}