<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HangoutPlace;
use App\Models\User;
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
                'user_id'     => Auth::id(), 
                'is_claimed'  => true,
                'is_verified' => false // [UPDATE] Set ke False (Pending) menunggu admin
            ]);
        } 
        // SKENARIO 2: User input manual tempat baru (Manual Add)
        elseif ($request->filled('new_name')) {
            
            // (Base Lat: -6.2..., Base Lng: 106.8...)
            $randomLat = -6.2 . rand(1000, 9999); 
            $randomLng = 106.8 . rand(1000, 9999);

            HangoutPlace::create([
                'user_id'       => Auth::id(),
                'name'          => $request->new_name,
                'category'      => $request->new_category ?? 'Coffee Shop',
                'address'       => $request->new_address ?? 'Jakarta Selatan',
                'description'   => 'Tempat hangout baru yang sedang hits.',
                'image'     => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1000&auto=format&fit=crop', // Default Image
                'is_claimed'    => true,
                'is_verified'   => false, 
                'viral_score'   => 50,
                'profile_views' => 0,
                'latitude'      => (float) $randomLat,
                'longitude'     => (float) $randomLng,
            ]);
        } else {
            return back()->with('error', 'Gagal memproses. Mohon pilih tempat atau isi data baru.');
        }

        // [UPDATE] Pesan sukses diubah agar user tahu harus menunggu
        return back()->with('success', 'Permintaan klaim berhasil dikirim! Mohon tunggu verifikasi Admin 1x24 jam.');
    }

    /**
     * Logika untuk update promo.
     */
    public function updatePromo(Request $request, $id)
    {
        $place = HangoutPlace::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Cek apakah sudah diverifikasi admin
        if (!$place->is_verified) {
            return back()->with('error', 'Akun bisnis Anda belum diverifikasi oleh Admin.');
        }

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
    // BAGIAN 2: FITUR ADMINISTRATOR (CONTROL ROOM)
    // =========================================================================

    /**
     * Menampilkan Dashboard Admin dengan Data Real & Antrian Verifikasi.
     */
    public function adminDashboard()
    {
        // 1. Total Traffic (Jumlah Profile Views seluruh tempat)
        $totalTraffic = HangoutPlace::sum('profile_views') ?? 0;
        
        // 2. Viral Spots (Tempat dengan score > 80)
        $viralCount = HangoutPlace::where('viral_score', '>', 80)->count();

        // 3. Ambil daftar klaim yang statusnya masih Pending (is_verified = 0)
        // Pastikan kolom 'is_verified' sudah ada di database
        $pendingClaims = HangoutPlace::where('is_claimed', true)
                                     ->where('is_verified', false)
                                     ->with('user') // Eager load data user
                                     ->latest()
                                     ->get();

        // 4. Most Wanted (Tempat dengan views tertinggi)
        $mostWantedPlace = HangoutPlace::orderByDesc('profile_views')->first();
        
        // 5. Weather Simulation
        $hour = now()->hour;
        $weatherCondition = ($hour >= 6 && $hour < 18) ? 'Cerah Berawan' : 'Sejuk Berawan';
        $temp = rand(28, 32); 
        $rainChance = rand(10, 60);

        // Format Angka Traffic
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

        // 6. Chart Data & Updates (Simulasi)
        $chartData = [
            'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'data' => [
                rand(100, 500), rand(150, 550), rand(200, 600), rand(300, 700), 
                rand(800, 1500), rand(1000, 2000), rand(500, 900)
            ]
        ];

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
                'image' => $latestPlace ? $latestPlace->image : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1000'
            ]
        ];

        return view('business.admin_dashboard', compact('metrics', 'chartData', 'updates', 'pendingClaims', 'totalTraffic'));
    }

    /**
     * Menyetujui klaim bisnis (Approve).
     */
    public function verifyClaim($id)
    {
        $place = HangoutPlace::findOrFail($id);
        
        // Ubah status jadi Verified
        $place->update(['is_verified' => true]);
                
        return back()->with('success', 'Bisnis berhasil diverifikasi & diterbitkan!');
    }

    /**
     * Menolak klaim bisnis (Reject).
     */
    public function rejectClaim($id)
    {
        $place = HangoutPlace::findOrFail($id);
        
        // Reset kepemilikan
        $place->update([
            'user_id'     => null,
            'is_claimed'  => false,
            'is_verified' => false
        ]);

        return back()->with('success', 'Permintaan klaim ditolak.');
    }
}