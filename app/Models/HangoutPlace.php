<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class HangoutPlace extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'facilities' => 'array',
        'promo_expires_at' => 'datetime',
        'is_claimed' => 'boolean',
        'is_verified' => 'boolean',
    ];

    // --- RELATIONSHIPS (Wajib Lengkap) ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'hangout_place_id', 'user_id');
    }

    // --- ACCESSORS ---

    public function getImageUrlAttribute()
    {
        // 1. Cek apakah kolom image_url kosong?
        if (empty($this->image_url)) {
            return 'https://placehold.co/600x400?text=No+Image';
        }

        // 2. Cek apakah ini link eksternal (Unsplash, dll)?
        if (Str::startsWith($this->image_url, ['http://', 'https://'])) {
            return $this->image_url;
        }

        // 3. Jika bukan link, berarti file upload lokal (storage)
        return asset('storage/' . $this->image_url);
    }

    public function getAvgRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?? 0;
    }

    // [FIX] Logika Crowd Status
    public function getCrowdStatusAttribute()
    {
        $activeVisitors = $this->checkins()
            ->where('created_at', '>=', now()->subHours(3))
            ->count();

        if ($activeVisitors > 20) return 'Penuh';
        if ($activeVisitors > 10) return 'Ramai';
        if ($activeVisitors > 5)  return 'Sedang';
        return 'Sepi';
    }
}