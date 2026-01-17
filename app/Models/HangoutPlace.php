<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class HangoutPlace extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // 1. Agar kolom 'facilities' (JSON) bisa dibaca sebagai Array di Blade
    protected $casts = [
        'facilities' => 'array',
        'promo_expires_at' => 'datetime',
    ];

    // --- RELATIONSHIPS (Wajib ada biar $place->reviews tidak error) ---
    
    // Relasi ke tabel reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi ke tabel bookmarks (User yang menyimpan tempat ini)
    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'hangout_place_id', 'user_id');
    }

    // --- ACCESSORS (Logika Pintar) ---

    // 1. Logika Foto (Yang sudah kita buat)
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return 'https://placehold.co/600x400?text=No+Image';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    // 2. Logika Rating Rata-rata ($place->avg_rating)
    public function getAvgRatingAttribute()
    {
        // Hitung rata-rata dari tabel reviews, bulatkan 1 desimal
        // Jika belum ada review, kembalikan 0
        return round($this->reviews()->avg('rating'), 1) ?? 0;
    }
}