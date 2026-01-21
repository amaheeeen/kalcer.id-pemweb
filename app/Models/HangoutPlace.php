<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // Ambil data mentah dari kolom 'image' di database
                $path = $attributes['image'] ?? null;

                // 1. Jika kosong, pakai placeholder
                if (!$path) {
                    return 'https://placehold.co/600x400?text=No+Image';
                }

                // 2. Jika link eksternal (Unsplash/Google), return langsung
                if (Str::startsWith($path, ['http://', 'https://'])) {
                    return $path;
                }

                // 3. Jika file lokal, tambahkan asset('storage/...')
                return asset('storage/' . $path);
            }
        );
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