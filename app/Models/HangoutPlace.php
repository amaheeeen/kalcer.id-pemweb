<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HangoutPlace extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Agar kolom fasilitas otomatis jadi Array saat diambil dari DB
    protected $casts = [
    'facilities' => 'array', 
    'is_verified' => 'boolean',
    'is_claimed' => 'boolean',
    'promo_expires_at' => 'datetime',
];

    // Relasi ke Review
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest(); // Review terbaru di atas
    }

    // Helper untuk hitung rata-rata rating otomatis
    public function getAvgRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
    
    // Relasi ke Pemilik (User)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}