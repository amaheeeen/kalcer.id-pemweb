<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class HangoutPlaceSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan data lama
        Schema::disableForeignKeyConstraints();
        DB::table('hangout_places')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Daftar Nama Tempat
        $spots = [
            ['name' => 'M Bloc Space', 'category' => 'Creative Space', 'query' => 'M Bloc Space Jakarta'],
            ['name' => 'Sarinah Thamrin', 'category' => 'Shopping', 'query' => 'Sarinah Jakarta'],
            ['name' => 'Hutan Kota GBK', 'category' => 'Public Park', 'query' => 'GBK Jakarta Park'],
            ['name' => 'Taman Literasi Blok M', 'category' => 'Public Park', 'query' => 'Blok M Jakarta'],
            ['name' => 'Tebet Eco Park', 'category' => 'Public Park', 'query' => 'Tebet Eco Park'],
            ['name' => 'Pantjoran PIK', 'category' => 'Culinary', 'query' => 'Chinatown Jakarta'],
            ['name' => 'Pos Bloc Jakarta', 'category' => 'Creative Space', 'query' => 'Pos Bloc Jakarta'],
            ['name' => 'Giyanti Coffee', 'category' => 'Coffee Shop', 'query' => 'Coffee Shop Aesthetic Jakarta'],
            ['name' => 'Anomali Coffee', 'category' => 'Coffee Shop', 'query' => 'Coffee Latte Art'],
            ['name' => 'Filosofi Kopi', 'category' => 'Coffee Shop', 'query' => 'Coffee Shop Jakarta'],
            ['name' => 'Urban Forest Cipete', 'category' => 'Public Park', 'query' => 'Urban Forest Jakarta'],
            ['name' => 'Chillax Sudirman', 'category' => 'Culinary', 'query' => 'Alfresco Dining Jakarta'],
            ['name' => 'Arborea Cafe', 'category' => 'Coffee Shop', 'query' => 'Forest Cafe'],
            ['name' => 'Langit Seduh', 'category' => 'Rooftop', 'query' => 'Rooftop Cafe Jakarta'],
            ['name' => 'Tanamur', 'category' => 'Bar', 'query' => 'Vintage Disco Jakarta'],
            ['name' => 'Row 9 Blok M', 'category' => 'Creative Space', 'query' => 'Industrial Cafe Jakarta'],
            ['name' => 'Cove at Batavia', 'category' => 'Culinary', 'query' => 'Pantai Indah Kapuk'],
            ['name' => 'Old Shanghai', 'category' => 'Culinary', 'query' => 'Chinese Temple Architecture'],
            ['name' => 'Aloha PIK 2', 'category' => 'Beach Club', 'query' => 'Tropical Beach Club'],
            ['name' => 'STUJA Coffee', 'category' => 'Coffee Shop', 'query' => 'Eco Friendly Coffee Shop'],
        ];

        $accessKey = env('UNSPLASH_ACCESS_KEY');

        foreach ($spots as $index => $spot) {
            
            // Default image
            $imageUrl = 'https://placehold.co/600x400?text=' . urlencode($spot['name']);

            // Panggil Unsplash API
            if ($accessKey) {
                try {
                    $response = Http::get('https://api.unsplash.com/search/photos', [
                        'client_id' => $accessKey,
                        'query' => $spot['query'],
                        'per_page' => 1,
                        'orientation' => 'landscape'
                    ]);

                    if ($response->successful() && !empty($response->json()['results'])) {
                        $imageUrl = $response->json()['results'][0]['urls']['regular'];
                        usleep(300000); // Delay 0.3s
                    }
                } catch (\Exception $e) {
                    // Fail silent
                }
            }

            //  Logika Penentuan Kepribadian
            $personality = 'ambivert';
            if (in_array($spot['category'], ['Coffee Shop', 'Public Park'])) {
                // Tempat santai biasanya cocok untuk Introvert/Ambivert
                $personality = (rand(0, 1) ? 'introvert' : 'ambivert');
            } elseif (in_array($spot['category'], ['Bar', 'Beach Club', 'Rooftop'])) {
                // Tempat berisik biasanya untuk Extrovert
                $personality = 'extrovert';
            } elseif ($spot['category'] == 'Creative Space') {
                $personality = 'ambivert';
            }

            // Simpan ke Database
            DB::table('hangout_places')->insert([
                'name' => $spot['name'],
                'category' => $spot['category'],
                'description' => 'Spot viral ' . $spot['name'] . ' yang wajib dikunjungi di Jakarta Selatan.',
                'address' => 'Jakarta Selatan, DKI Jakarta',
                'latitude' => -6.2 + ($index * 0.002), // Geser koordinat sedikit biar gak numpuk di peta
                'longitude' => 106.8 + ($index * 0.002),
                'image' => $imageUrl,
                'viral_score' => rand(50, 99), // Tambah viral score acak
                'profile_views' => rand(100, 5000),
                
                'price_range' => rand(1, 3), // 1=$, 2=$$, 3=$$$
                'personality_type' => $personality, 
                // -----------------------------

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            $this->command->info("Seeded: " . $spot['name'] . " ($personality)");
        }
    }
}