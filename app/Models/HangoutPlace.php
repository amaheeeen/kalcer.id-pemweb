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
    ];

    // Relasi ke Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    // Relasi ke Pemilik (User)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}