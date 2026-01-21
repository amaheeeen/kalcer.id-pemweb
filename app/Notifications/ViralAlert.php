<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\HangoutPlace;

class ViralAlert extends Notification
{
    use Queueable;

    public $place;

    public function __construct(HangoutPlace $place)
    {
        $this->place = $place;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Simpan ke database agar muncul di lonceng
    }

    public function toArray(object $notifiable): array
    {
        return [
            'place_id' => $this->place->id,
            'title' => '🔥 Viral Alert: ' . $this->place->name,
            'message' => 'Tempat ini baru saja mendapatkan skor viral tinggi! Cek sekarang.',
            'image' => $this->place->image,
            'time' => now(),
        ];
    }
}