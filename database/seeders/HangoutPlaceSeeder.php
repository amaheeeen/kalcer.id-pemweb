<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HangoutPlace;
use Carbon\Carbon;

class HangoutPlaceSeeder extends Seeder
{
    public function run(): void
    {
        // Helper untuk URL Unsplash yang stabil
        $baseUrl = "https://images.unsplash.com/photo-";
        $params = "?auto=format&fit=crop&w=800&q=80";

        $places = [
            // --- BLOK M & MELAWAI ---
            [
                'name' => 'M Bloc Space',
                'address' => 'Jl. Panglima Polim No.37, Melawai, Kec. Kby. Baru, Jakarta Selatan',
                'latitude' => -6.244229,
                'longitude' => 106.798340,
                'category' => 'Creative Hub',
                'facilities' => ['WiFi', 'Outdoor Area', 'Live Music', 'Toilet'],
                'operational_hours' => '10:00 - 22:00',
                'description' => 'Ruang kreatif publik di bekas perumahan Peruri. Vibes retro industrial yang hits.',
                // Foto: Industrial Brick Building (Mirip M Bloc)
                'image_url' => $baseUrl . '1572061489035-7c6030999882' . $params, 
                'crowd_level' => 'ramai',
                'viral_score' => 95,
                'is_verified' => true,
                'is_claimed' => true,
                'promo_text' => 'Diskon 10% All Tenants pake QRIS',
                'promo_expires_at' => Carbon::now()->addDays(3),
                'profile_views' => 12500,
            ],
            [
                'name' => 'Taman Literasi Martha Christina Tiahahu',
                'address' => 'Jl. Sisingamangaraja, Melawai, Kec. Kby. Baru, Jakarta Selatan',
                'latitude' => -6.242800,
                'longitude' => 106.800500,
                'category' => 'Public Space',
                'facilities' => ['Library', 'Park', 'Amphitheater', 'Kids Zone'],
                'operational_hours' => '07:00 - 22:00',
                'description' => 'Taman kota modern dengan perpustakaan estetik. Cocok untuk baca buku sore-sore.',
                // Foto: Modern Concrete Park
                'image_url' => $baseUrl . '1597424219747-920b72df4865' . $params,
                'crowd_level' => 'sedang',
                'viral_score' => 88,
                'is_verified' => true,
                'profile_views' => 8400,
            ],
            [
                'name' => 'Filosofi Kopi Melawai',
                'address' => 'Jl. Melawai VI No.25, Melawai, Jakarta Selatan',
                'latitude' => -6.243500,
                'longitude' => 106.801200,
                'category' => 'Coffee Shop',
                'facilities' => ['WiFi', 'AC', 'Smoking Area'],
                'operational_hours' => '07:00 - 23:00',
                'description' => 'Kedai kopi ikonik. Tempat ngopi serius dengan suasana industrial yang hangat.',
                // Foto: Barista & Coffee Machine
                'image_url' => $baseUrl . '1495474472287-4d71bcdd2085' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 85,
                'is_verified' => true,
            ],
            [
                'name' => 'Iron Fist',
                'address' => 'Jl. Melawai VIII No.3, Melawai, Jakarta Selatan',
                'latitude' => -6.244000,
                'longitude' => 106.802000,
                'category' => 'Restaurant',
                'facilities' => ['AC', 'Dining', 'Bar'],
                'operational_hours' => '11:00 - 23:00',
                'description' => 'Fusion Chinese food dengan vibe modern neon dan claypot rice juara.',
                // Foto: Chinese Food / Dimsum Vibe
                'image_url' => $baseUrl . '1563245316166-bd7b1156263e' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 92,
                'is_verified' => true,
            ],
            [
                'name' => 'Futago Ya',
                'address' => 'Jl. Sultan Hasanudin Dalam No.24, Melawai, Jakarta Selatan',
                'latitude' => -6.243100,
                'longitude' => 106.803500,
                'category' => 'Restaurant',
                'facilities' => ['AC', 'Japanese Style'],
                'operational_hours' => '10:00 - 22:00',
                'description' => 'Creamy Udon dan Gyoza yang viral di TikTok. Antrian bisa panjang!',
                // Foto: Japanese Udon / Ramen
                'image_url' => $baseUrl . '1552611052-33e04de081de' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 94,
                'is_verified' => true,
            ],
            [
                'name' => 'Row 9',
                'address' => 'Jl. Bulungan No.9, Kramat Pela, Jakarta Selatan',
                'latitude' => -6.241500,
                'longitude' => 106.797800,
                'category' => 'Creative Hub',
                'facilities' => ['Parking', 'Coffee', 'Bakery'],
                'operational_hours' => '08:00 - 22:00',
                'description' => 'Compound space baru di Bulungan. Ada Suasana Kopi dan bakery enak.',
                // Foto: Clean minimalist compound
                'image_url' => $baseUrl . '1554118811-1e0d58224f24' . $params,
                'crowd_level' => 'sedang',
                'viral_score' => 78,
                'is_verified' => true,
            ],
            [
                'name' => 'Little League',
                'address' => 'Jl. Prof. Joko Sutono SH No.21, Petogogan, Jakarta Selatan',
                'latitude' => -6.238900,
                'longitude' => 106.805500,
                'category' => 'Coffee Shop',
                'facilities' => ['WiFi', 'Meeting Room', 'Outdoor'],
                'operational_hours' => '08:00 - 21:00',
                'description' => 'Kafe luas cocok buat WFC (Work From Cafe) dengan suasana tenang.',
                // Foto: Coworking Cafe Vibe
                'image_url' => $baseUrl . '1527196647628-4610e2509d3c' . $params,
                'crowd_level' => 'sedang',
                'viral_score' => 75,
                'is_verified' => true,
            ],
            [
                'name' => 'Haka Dimsum Blok M',
                'address' => 'Jl. Sultan Hasanudin Dalam No.3, Melawai, Jakarta Selatan',
                'latitude' => -6.243300,
                'longitude' => 106.802800,
                'category' => 'Restaurant',
                'facilities' => ['24 Hours', 'AC'],
                'operational_hours' => '24 Jam',
                'description' => 'Dimsum halal 24 jam. Penyelamat lapar tengah malam anak Jaksel.',
                // Foto: Dimsum Basket
                'image_url' => $baseUrl . '1496116218417-1a781b1c416c' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 90,
                'is_verified' => true,
            ],
            [
                'name' => 'Uma Oma Cafe',
                'address' => 'Jl. Melawai I No.28, Melawai, Jakarta Selatan',
                'latitude' => -6.243800,
                'longitude' => 106.800200,
                'category' => 'Coffee Shop',
                'facilities' => ['AC', 'WiFi', 'Nostalgic Vibe'],
                'operational_hours' => '10:00 - 22:00',
                'description' => 'Kafe unik yang dilayani oleh lansia (Oma), suasananya seperti rumah nenek.',
                // Foto: Homey Vintage Interior
                'image_url' => $baseUrl . '1521017432531-fbd92d768814' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 97,
                'is_verified' => true,
            ],
            [
                'name' => 'Taman Langsat',
                'address' => 'Jl. Barito, Kramat Pela, Jakarta Selatan',
                'latitude' => -6.245500,
                'longitude' => 106.795000,
                'category' => 'Public Space',
                'facilities' => ['Jogging Track', 'Lake', 'Toilet'],
                'operational_hours' => '06:00 - 18:00',
                'description' => 'Hidden park yang asri dan tenang, cocok untuk piknik atau lari sore.',
                // Foto: Green Park with Trees
                'image_url' => $baseUrl . '1496568813658-44a243521959' . $params,
                'crowd_level' => 'sepi',
                'viral_score' => 80,
                'is_verified' => true,
            ],

            // --- SENOPATI & GUNAWARMAN ---
            [
                'name' => 'Ashtari (Ashta District 8)',
                'address' => 'District 8, SCBD Lot 28, Jakarta Selatan',
                'latitude' => -6.229700,
                'longitude' => 106.805800,
                'category' => 'Creative Hub',
                'facilities' => ['Mall', 'Rooftop', 'Parking'],
                'operational_hours' => '10:00 - 22:00',
                'description' => 'Spot bengong di balkon dengan pemandangan gedung pencakar langit SCBD.',
                // Foto: Skyscrapers / Modern Building
                'image_url' => $baseUrl . '1486406146926-c627a92ad1ab' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 93,
                'is_verified' => true,
                'is_claimed' => true,
                'promo_text' => 'Free Parking 2 Hours (Weekend)',
                'promo_expires_at' => Carbon::now()->addDays(7),
                'profile_views' => 15000,
            ],
            [
                'name' => 'Monsieur Spoon Senopati',
                'address' => 'Jl. Senopati No.64, Jakarta Selatan',
                'latitude' => -6.233000,
                'longitude' => 106.808000,
                'category' => 'Restaurant',
                'facilities' => ['Bakery', 'Outdoor', 'Valet'],
                'operational_hours' => '07:00 - 22:00',
                'description' => 'Bakery ala Perancis. Cromboloni-nya sempat bikin antrian mengular.',
                // Foto: Pastry / Croissant
                'image_url' => $baseUrl . '1509365465985-25d11c17e812' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 96,
                'is_verified' => true,
            ],
            [
                'name' => 'Pison Senopati',
                'address' => 'Jl. Kertanegara No.70, Jakarta Selatan',
                'latitude' => -6.235500,
                'longitude' => 106.805000,
                'category' => 'Coffee Shop',
                'facilities' => ['Live Music', 'Food', 'Valet'],
                'operational_hours' => '08:00 - 23:00',
                'description' => 'Vibe Bali di Jakarta. Kopi enak dan makanan berat yang ngenyangin.',
                // Foto: Coffee and Brunch Food
                'image_url' => $baseUrl . '1554118811-1e0d58224f24' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 89,
                'is_verified' => true,
            ],

            // --- KEMANG ---
            [
                'name' => 'Como Park',
                'address' => 'Jl. Kemang Timur No.998, Bangka, Jakarta Selatan',
                'latitude' => -6.265500,
                'longitude' => 106.822000,
                'category' => 'Public Space',
                'facilities' => ['Dog Park', 'Pizza', 'Coffee'],
                'operational_hours' => '08:00 - 20:00',
                'description' => 'Surga buat pecinta anjing. Ada Pizza Place yang legendaris.',
                // Foto: Dog Park / Outdoor vibes
                'image_url' => $baseUrl . '1596230529625-7ee10f7b09b6' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 91,
                'is_verified' => true,
            ],
            [
                'name' => 'Dia.Lo.Gue',
                'address' => 'Jl. Kemang Selatan No.99A, Jakarta Selatan',
                'latitude' => -6.273000,
                'longitude' => 106.818500,
                'category' => 'Creative Hub',
                'facilities' => ['Art Gallery', 'Shop', 'Cafe'],
                'operational_hours' => '09:00 - 18:00',
                'description' => 'Artspace yang menyatu dengan kafe. Tangga melayang-nya ikonik banget.',
                // Foto: Art Gallery / Minimalist Stairs
                'image_url' => $baseUrl . '1513364776144-60967b0f800f' . $params,
                'crowd_level' => 'sedang',
                'viral_score' => 82,
                'is_verified' => true,
            ],
            [
                'name' => 'Ecaps',
                'address' => 'Jl. Kemang Raya No.17, Jakarta Selatan',
                'latitude' => -6.260500,
                'longitude' => 106.815000,
                'category' => 'Restaurant',
                'facilities' => ['Eco Friendly', 'Garden', 'WiFi'],
                'operational_hours' => '10:00 - 22:00',
                'description' => 'Urban farm to table concept. Tempatnya asri dan makanannya organik.',
                // Foto: Green Garden Restaurant
                'image_url' => $baseUrl . '1466978913421-dad938661248' . $params,
                'crowd_level' => 'sepi',
                'viral_score' => 60,
                'is_verified' => true,
            ],

            // --- CIPETE & FATMAWATI ---
            [
                'name' => 'Urban Forest Cipete',
                'address' => 'Jl. RS. Fatmawati Raya No.45, Cilandak, Jakarta Selatan',
                'latitude' => -6.275500,
                'longitude' => 106.797000,
                'category' => 'Public Space',
                'facilities' => ['Playground', 'Restaurants', 'Gelato'],
                'operational_hours' => '07:00 - 22:00',
                'description' => 'Hutan kota mini dengan berbagai tenant makanan. Vibesnya kayak di Sentul.',
                // Foto: Green Outdoor Area
                'image_url' => $baseUrl . '1621891363654-c97664320298' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 98,
                'is_verified' => true,
                'is_claimed' => true,
                'promo_text' => 'Buy 1 Get 1 Gelato',
                'promo_expires_at' => Carbon::now()->addDays(5),
            ],
            [
                'name' => 'Tuku Kopi Cipete',
                'address' => 'Jl. Cipete Raya No.7, Jakarta Selatan',
                'latitude' => -6.272000,
                'longitude' => 106.806500,
                'category' => 'Coffee Shop',
                'facilities' => ['Takeaway'],
                'operational_hours' => '07:00 - 21:00',
                'description' => 'Pewarta Kopi Tetangga yang legendaris. Kecil tempatnya, besar namanya.',
                // Foto: Coffee Takeaway
                'image_url' => $baseUrl . '1514432324607-a09d9b4aefdd' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 90,
                'is_verified' => true,
            ],

            // --- TEBET & GANDARIA ---
            [
                'name' => 'Tebet Eco Park',
                'address' => 'Jl. Tebet Barat Raya, Tebet, Jakarta Selatan',
                'latitude' => -6.238400,
                'longitude' => 106.852500,
                'category' => 'Public Space',
                'facilities' => ['Playground', 'Jogging Track', 'Bridge'],
                'operational_hours' => '06:00 - 18:00',
                'description' => 'Taman kota terbaik di Jakarta saat ini. Jembatan infinity-nya keren buat foto.',
                // Foto: Modern Bridge / Park
                'image_url' => $baseUrl . '1563514227147-6d2ff665a6a0' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 99,
                'is_verified' => true,
            ],
            [
                'name' => '1/15 Coffee Gandaria',
                'address' => 'Jl. Gandaria I No.63, Jakarta Selatan',
                'latitude' => -6.240500,
                'longitude' => 106.790000,
                'category' => 'Coffee Shop',
                'facilities' => ['WiFi', 'Spacious', 'Meeting'],
                'operational_hours' => '07:00 - 21:00',
                'description' => 'Cabang pertama 1/15. Langit-langit tinggi, cahaya natural bagus.',
                // Foto: Spacious Bright Cafe
                'image_url' => $baseUrl . '1554118811-1e0d58224f24' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 86,
                'is_verified' => true,
            ],
            [
                'name' => 'Kampung Gallery',
                'address' => 'Jl. Mesjid Al Huda No.1, Kebayoran Lama, Jakarta Selatan',
                'latitude' => -6.239500,
                'longitude' => 106.782000,
                'category' => 'Creative Hub',
                'facilities' => ['Antiques', 'Music', 'Cheap Food'],
                'operational_hours' => '10:00 - 23:00',
                'description' => 'Hidden gem di dekat stasiun Kebayoran. Penuh barang antik dan vibes 90an.',
                // Foto: Antique Shop / Vintage
                'image_url' => $baseUrl . '1551632436-cbf8dd354ca8' . $params,
                'crowd_level' => 'sedang',
                'viral_score' => 76,
                'is_verified' => true,
            ],

            // --- TAMBAHAN RANDOM SOUTH JAKARTA ---
            [
                'name' => 'Hutan Kota GBK',
                'address' => 'Pintu 5 GBK, Senayan, Jakarta Selatan',
                'latitude' => -6.222500,
                'longitude' => 106.808000,
                'category' => 'Public Space',
                'facilities' => ['Picnic Area', 'View SCBD'],
                'operational_hours' => '06:00 - 18:00',
                'description' => 'Central Park-nya Jakarta. View gedung tinggi SCBD dari sini juara banget.',
                // Foto: Park with City View
                'image_url' => $baseUrl . '1588872083623-a2624a919077' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 97,
                'is_verified' => true,
            ],
            [
                'name' => 'Lucy in The Sky - SCBD',
                'address' => 'Fairgrounds, SCBD Lot 14, Jakarta Selatan',
                'latitude' => -6.226000,
                'longitude' => 106.809500,
                'category' => 'Restaurant',
                'facilities' => ['Rooftop', 'Bar', 'DJ'],
                'operational_hours' => '16:00 - 02:00',
                'description' => 'Bohemian rooftop bar. Tempat party paling hits di Jaksel.',
                // Foto: Rooftop Bar
                'image_url' => $baseUrl . '1570554886111-e811eb304219' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 94,
                'is_verified' => true,
            ],
            [
                'name' => 'Suwe Ora Jamu M Bloc',
                'address' => 'M Bloc Space, Melawai, Jakarta Selatan',
                'latitude' => -6.244100,
                'longitude' => 106.798500,
                'category' => 'Restaurant',
                'facilities' => ['Traditional Drink', 'Vintage'],
                'operational_hours' => '10:00 - 22:00',
                'description' => 'Minum jamu tapi tempatnya gaul. Wajib coba beras kencurnya.',
                // Foto: Herbal Drink / Traditional
                'image_url' => $baseUrl . '1514361892635-6b07e31e75f9' . $params,
                'crowd_level' => 'sedang',
                'viral_score' => 77,
                'is_verified' => true,
            ],
            [
                'name' => 'Tokyo Skipjack',
                'address' => 'Jl. Bulungan No.16, Kramat Pela, Jakarta Selatan',
                'latitude' => -6.242000,
                'longitude' => 106.796500,
                'category' => 'Restaurant',
                'facilities' => ['Steak', 'Outdoor'],
                'operational_hours' => '11:00 - 23:00',
                'description' => 'Steak enak dengan harga masuk akal di Bulungan.',
                // Foto: Steak Food
                'image_url' => $baseUrl . '1600891964092-4316c288032e' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 81,
                'is_verified' => true,
            ],
            [
                'name' => 'Cork&Screw Country Club',
                'address' => 'Senayan National Golf Club, Jakarta Selatan',
                'latitude' => -6.223000,
                'longitude' => 106.797000,
                'category' => 'Restaurant',
                'facilities' => ['Pool', 'Golf View', 'Daybed'],
                'operational_hours' => '10:00 - 00:00',
                'description' => 'Berasa di Bali tapi di Senayan. Bisa santai di pinggir kolam.',
                // Foto: Poolside Dining
                'image_url' => $baseUrl . '1572331165267-854da2b00dc1' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 96,
                'is_verified' => true,
            ],
            [
                'name' => 'Union Pondok Indah',
                'address' => 'Pondok Indah Mall 3, Ground Floor, Jakarta Selatan',
                'latitude' => -6.264500,
                'longitude' => 106.783000,
                'category' => 'Restaurant',
                'facilities' => ['Bar', 'Bakery', 'Mall Access'],
                'operational_hours' => '10:00 - 00:00',
                'description' => 'Red Velvet Cake-nya juara. Tempat wajib buat social climbing Jaksel.',
                // Foto: Fancy Restaurant / Bar
                'image_url' => $baseUrl . '1559339352-11d035aa65de' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 95,
                'is_verified' => true,
                'is_claimed' => true,
                'promo_text' => 'Buy 1 Get 1 Cocktail (Thu)',
                'promo_expires_at' => Carbon::now()->addDays(2),
            ],
            [
                'name' => 'Foya M Bloc',
                'address' => 'M Bloc Space, Panglima Polim, Jakarta Selatan',
                'latitude' => -6.244400,
                'longitude' => 106.798400,
                'category' => 'Restaurant',
                'facilities' => ['Bar', 'Live DJ', 'Party'],
                'operational_hours' => '15:00 - 00:00',
                'description' => 'Tempat party paling baru di M Bloc. Design interiornya retro futuristik.',
                // Foto: Neon Bar / Party
                'image_url' => $baseUrl . '1572116469696-31de0f17cc34' . $params,
                'crowd_level' => 'ramai',
                'viral_score' => 89,
                'is_verified' => true,
            ],
            [
                'name' => 'Claypot Popo Melawai',
                'address' => 'Jl. Melawai 9 No.38, Jakarta Selatan',
                'latitude' => -6.243900,
                'longitude' => 106.801500,
                'category' => 'Restaurant',
                'facilities' => ['Halal', 'Quick Eat'],
                'operational_hours' => '11:00 - 21:00',
                'description' => 'Nasi Claypot siram telur asin yang comfort food banget. Tempatnya kecil tapi ngangenin.',
                // Foto: Claypot Food
                'image_url' => $baseUrl . '1512058564366-18510be2db19' . $params,
                'crowd_level' => 'penuh',
                'viral_score' => 85,
                'is_verified' => true,
            ],
        ];

        // --- DUMMY DATA ---
        // Array foto kopi random agar tidak monoton
        $coffeeImages = [
            '1497935586351-b67a49e012bf',
            '1509042239860-f550ce710b93',
            '1511920170033-f8396924c348',
            '1447933601403-0c673ec19664',
            '1507133750069-b6d338dd0957'
        ];

        for ($i = 1; $i <= 10; $i++) {
            $randomImage = $coffeeImages[array_rand($coffeeImages)];
            
            $places[] = [
                'name' => 'Kopi Senja ' . $i,
                'address' => 'Jl. Radio Dalam Raya No.' . rand(1, 100) . ', Jakarta Selatan',
                'latitude' => -6.255000 + (rand(-100, 100) / 10000),
                'longitude' => 106.790000 + (rand(-100, 100) / 10000),
                'category' => 'Coffee Shop',
                'facilities' => ['WiFi', 'Smoking Area'],
                'operational_hours' => '08:00 - 22:00',
                'description' => 'Tempat ngopi santai di pinggir Radio Dalam.',
                'image_url' => $baseUrl . $randomImage . $params,
                'crowd_level' => 'sepi',
                'viral_score' => rand(50, 70),
                'is_verified' => true,
                'profile_views' => rand(100, 500),
            ];
        }

        foreach ($places as $place) {
            $data = array_merge([
                'crowd_level' => 'sepi',
                'viral_score' => 0,
                'is_verified' => false,
                'is_claimed' => false,
                'profile_views' => 0,
                'promo_text' => null,
                'promo_expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ], $place);

            HangoutPlace::create($data);
        }
    }
}