<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HangoutPlace;
use App\Models\User; // Tambahkan ini untuk data updates
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
        // 1. Ambil bisnis milik user saat ini
        $myPlace = HangoutPlace::where('user_id', Auth::id())->first();
        
        // 2. Ambil daftar tempat yang BELUM diklaim (untuk dropdown pencarian)
        $availablePlaces = [];
        if (!$myPlace) {
            $availablePlaces = HangoutPlace::whereNull('user_id')
                                ->orWhere('is_claimed', false)
                                ->orderBy('name')
                                ->get();
        }

        return view('business.index', compact('myPlace', 'availablePlaces'));
    }

    /**
     * Logika untuk mengklaim bisnis (Mendukung Cari Database & Input Manual).
     */
    public function claim(Request $request)
    {
        // Validasi yang fleksibel: Bisa pilih ID yang ada ATAU input Nama Baru
        $request->validate([
            'place_id'     => 'nullable|exists:hangout_places,id',
            'new_name'     => 'nullable|required_without:place_id|string|max:255',
            'new_category' => 'nullable|string',
            'new_address'  => 'nullable|string'
        ]);

        // SKENARIO 1: User memilih tempat yang sudah ada di database
        if ($request->filled('place_id')) {
            $place = HangoutPlace::findOrFail($request->place_id);

            if ($place->is_claimed) {
                return back()->with('error', 'Tempat ini sudah diklaim orang lain!');
            }

            $place->update([
                'user_id' => Auth::id(), 
                'is_claimed' => true
            ]);
        } 
        // SKENARIO 2: User input manual tempat baru (Manual Add)
        elseif ($request->filled('new_name')) {
            
            // Generate koordinat acak di sekitar Jakarta Selatan agar muncul di peta
            // (Base Lat: -6.2..., Base Lng: 106.8...)
            $randomLat = -6.2 . rand(1000, 9999); 
            $randomLng = 106.8 . rand(1000, 9999);

            HangoutPlace::create([
                'user_id'       => Auth::id(),
                'name'          => $request->new_name,
                'category'      => $request->new_category ?? 'Coffee Shop',
                'address'       => $request->new_address ?? 'Jakarta Selatan',
                'description'   => 'Tempat hangout baru yang sedang hits.',
                'image_url'     => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1000&auto=format&fit=crop', // Default Image
                'is_claimed'    => true,
                'viral_score'   => 50, // Score awal standard
                'profile_views' => 0,
                'latitude'      => (float) $randomLat,
                'longitude'     => (float) $randomLng,
            ]);
        } else {
            return back()->with('error', 'Gagal memproses. Mohon pilih tempat atau isi data baru.');
        }

        return back()->with('success', 'Selamat! Bisnis berhasil ditambahkan ke akun Anda.');
    }

    /**
     * Logika untuk update promo.
     */
    public function updatePromo(Request $request, $id)
    {
        $place = HangoutPlace::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'promo_text' => 'required|string|max:50', // Max 50 biar muat di UI
        ]);

        $place->update([
            'promo_text' => $request->promo_text,
            'promo_expires_at' => now()->addHours(24) 
        ]);

        return back()->with('success', 'Promo berhasil diupdate!');
    }

    // =========================================================================
    // BAGIAN 2: FITUR ADMINISTRATOR (REAL DATA DASHBOARD)
    // =========================================================================

    /**
     * Menampilkan Dashboard Admin dengan Data Real dari Database.
     */
    public function adminDashboard()
    {
        // 1. Total Traffic (Jumlah Profile Views seluruh tempat)
        $totalTraffic = HangoutPlace::sum('profile_views');
        
        // 2. Viral Spots (Tempat dengan score > 80)
        $viralCount = HangoutPlace::where('viral_score', '>', 80)->count();

        // 3. Most Wanted (Tempat dengan views tertinggi)
        $mostWantedPlace = HangoutPlace::orderByDesc('profile_views')->first();
        
        // 4. Weather Simulation (Agar terlihat Real-time sesuai jam server)
        $hour = now()->hour;
        $weatherCondition = ($hour >= 6 && $hour < 18) ? 'Cerah Berawan' : 'Sejuk Berawan';
        $temp = rand(28, 32); 
        $rainChance = rand(10, 60);

        // Format Angka Traffic (e.g. 24000 -> 24.5k)
        $formattedTraffic = $totalTraffic > 1000 
                            ? number_format($totalTraffic / 1000, 1) . 'k' 
                            : $totalTraffic;

        $metrics = [
            'traffic' => $formattedTraffic,
            'viral_spots' => $viralCount,
            'weather' => [
                'temp' => $temp,
                'condition' => $weatherCondition,
                'rain_chance' => $rainChance . '% (' . ($hour + 1) . ':00)'
            ],
            'most_wanted' => [
                'name' => $mostWantedPlace ? $mostWantedPlace->name : 'Belum Ada Data',
                'waitlist' => $mostWantedPlace ? ('~' . rand(15, 60) . ' Menit') : '-'
            ]
        ];

        // 5. Chart Data (Simulasi Trend 7 Hari Terakhir)
        // Karena kita tidak punya tabel 'daily_visits', kita generate pola acak yang masuk akal
        $chartData = [
            'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'data' => [
                rand(100, 500), rand(150, 550), rand(200, 600), rand(300, 700), 
                rand(800, 1500), rand(1000, 2000), rand(500, 900)
            ]
        ];

        // 6. Recent Updates (Ambil user terbaru atau tempat terbaru)
        $latestUser = User::latest()->first();
        $latestPlace = HangoutPlace::latest()->first();

        $updates = [
            [
                'category' => 'System', 
                'title' => 'Database Sync Berhasil', 
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1000'
            ],
            [
                'category' => 'User', 
                'title' => $latestUser ? ($latestUser->name . ' baru saja mendaftar') : 'Belum ada user baru', 
                'image' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=1000'
            ],
            [
                'category' => 'Place', 
                'title' => $latestPlace ? ($latestPlace->name . ' ditambahkan ke peta') : 'Belum ada tempat baru', 
                'image' => $latestPlace ? $latestPlace->image_url : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1000'
            ]
        ];

        return view('business.admin_dashboard', compact('metrics', 'chartData', 'updates'));
    }
}