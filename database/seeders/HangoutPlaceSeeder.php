<?php

namespace Database\Seeders;

use App\Models\HangoutPlace;
use App\Models\User;
use Illuminate\Database\Seeder;

class HangoutPlaceSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan User Owner ada
        $owner = User::firstOrCreate(
            ['email' => 'curator@kalcer.id'],
            [
                'name' => 'Chief Curator',
                'password' => bcrypt('password'),
                'role' => 'business_owner',
                'phone_number' => '08111222333'
            ]
        );

        $places = [
            [
                'name' => 'Kurasu Kissaten',
                'address' => 'Jl. Iskandarsyah I No.9, Melawai, Jakarta Selatan',
                'latitude' => -6.244229,
                'longitude' => 106.802870,
                'category' => 'Coffee Shop',
                'facilities' => ['Slow Bar', 'Vinyl Player', 'Listening Corner', 'Limited Seats'],
                'operational_hours' => '07:00 - 20:00',
                'description' => 'Bukan tempat buat meeting berisik. Kurasu menghadirkan pengalaman kissaten autentik dengan presisi brewing ala Kyoto. Vibes tenang, pencahayaan warm, dan seleksi beans yang dikurasi ketat. Cocok buat kontemplasi atau deep work sendirian.',
                'viral_score' => 88,
                'crowd_level' => 'sedang',
                'image_url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80',
                'is_verified' => true,
            ],
            [
                'name' => 'Ecaps',
                'address' => 'Jl. Kemang Raya No. 17, Jakarta Selatan',
                'latitude' => -6.255977,
                'longitude' => 106.811565,
                'category' => 'Eco-Space',
                'facilities' => ['Pet Friendly', 'Zero Waste Store', 'Botanical Garden', 'Outdoor AC'],
                'operational_hours' => '08:00 - 22:00',
                'description' => 'Hidden gem di tengah chaotic-nya Kemang. Mengusung konsep conscious living dengan area outdoor yang rimbun tapi tetap adem. Menu plant-based mereka solid, bukan sekadar gimmick. Sering masuk FYP karena estetikanya yang raw dan unpolished.',
                'viral_score' => 92,
                'crowd_level' => 'ramai',
                'image_url' => 'https://images.unsplash.com/photo-1466721591366-2d5fba72006d?auto=format&fit=crop&w=800&q=80',
                'is_verified' => true,
            ],
            [
                'name' => 'Row 9',
                'address' => 'Jl. Bulungan No. 9, Blok M, Jakarta Selatan',
                'latitude' => -6.242392,
                'longitude' => 106.797825,
                'category' => 'Creative Hub',
                'facilities' => ['Bakery', 'Exhibition Space', 'Rooftop', 'Valet Parking'],
                'operational_hours' => '10:00 - 23:00',
                'description' => 'Pusat gravitasi baru anak kreatif Jaksel. Arsitekturnya industrial-brutalist yang mendominasi feed Instagram bulan ini. Bakery di lantai dasar selalu sold out sebelum jam 3 sore. Datang pagi kalau ngga mau waitlist panjang.',
                'viral_score' => 96,
                'crowd_level' => 'penuh',
                'image_url' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=800&q=80',
                'is_verified' => true,
            ],
            [
                'name' => 'Taman Literasi Martha Tiahahu',
                'address' => 'Jl. Sisingamangaraja, Blok M, Jakarta Selatan',
                'latitude' => -6.243534,
                'longitude' => 106.799738,
                'category' => 'Public Space',
                'facilities' => ['Perpustakaan', 'Amphitheater', 'MRT Access', 'Free WiFi'],
                'operational_hours' => '07:00 - 22:00',
                'description' => 'Oasis hijau di tengah beton Blok M. Spot valid buat people watching atau baca buku sore hari. Akses langsung ke MRT bikin tempat ini accessible tapi konsekuensinya selalu padat saat golden hour.',
                'viral_score' => 85,
                'crowd_level' => 'sedang',
                'image_url' => 'https://images.unsplash.com/photo-1596280685601-e231876543b5?auto=format&fit=crop&w=800&q=80',
                'is_verified' => true,
            ],
        ];

        foreach ($places as $place) {
            HangoutPlace::create(array_merge($place, ['user_id' => $owner->id]));
        }
    }
}
