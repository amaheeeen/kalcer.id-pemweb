<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Default (untuk login test)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password', // Password akan di-hash otomatis oleh model User
                'email_verified_at' => now(), 
                'role' => 'user', // 'user' atau 'admin'
            ]
        );

        // 2. Panggil Seeder Lain (Data Tempat Hangout)
        $this->call(HangoutPlaceSeeder::class);
    }
}