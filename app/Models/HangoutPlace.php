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
        'is_verified' => 'boolean', // [PENTING] Tambahkan ini
    ];

    // --- RELATIONSHIPS (Wajib Lengkap) ---
    
    // [FIX 500 ERROR] Relasi ini WAJIB ADA karena dipanggil di Admin Dashboard
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi Checkin (Fitur No. 2)
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
        if (empty($this->image)) {
            return 'https://placehold.co/600x400?text=No+Image';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getAvgRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?? 0;
    }

    // [FIX] Logika Crowd Status (Fitur No. 2)
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