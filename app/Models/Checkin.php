<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    // Tambahkan relasi ke tabel checkins
public function checkins()
{
    return $this->hasMany(Checkin::class);
}

// Fitur Real-time Crowd Check (Sesuai SRS)
public function getCrowdStatusAttribute()
{
    // Hitung check-in dalam 3 jam terakhir
    $activeVisitors = $this->checkins()
        ->where('created_at', '>=', now()->subHours(3))
        ->count();

    // Logika sederhana penentuan status (Bisa disesuaikan threshold-nya)
    if ($activeVisitors > 20) return 'Penuh';
    if ($activeVisitors > 10) return 'Ramai';
    if ($activeVisitors > 5)  return 'Sedang';
    return 'Sepi';
}

// Helper untuk warna badge (biar UI cantik)
public function getCrowdColorAttribute()
{
    return match($this->crowd_status) {
        'Penuh' => 'bg-red-500 text-white',
        'Ramai' => 'bg-orange-500 text-white',
        'Sedang' => 'bg-yellow-500 text-white',
        'Sepi'   => 'bg-green-500 text-white',
    };
}
}
