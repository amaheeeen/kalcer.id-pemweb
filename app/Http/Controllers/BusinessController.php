<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HangoutPlace;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function index()
    {
        // Ambil tempat milik user yang sedang login (user_id)
        $myPlace = HangoutPlace::where('user_id', Auth::id())->first();
        return view('business.dashboard', compact('myPlace'));
    }

    public function claim(Request $request)
    {
        $request->validate(['place_id' => 'required|exists:hangout_places,id']);

        $place = HangoutPlace::findOrFail($request->place_id);

        if ($place->is_claimed) {
            return back()->with('error', 'Tempat ini sudah diklaim orang lain!');
        }

        $place->update([
            'user_id' => Auth::id(), // Set user yang login sebagai pemilik
            'is_claimed' => true
        ]);

        return back()->with('success', 'Selamat! Bisnis berhasil diklaim.');
    }

    public function updatePromo(Request $request, $id)
    {
        // Pastikan yang update adalah pemilik asli (user_id)
        $place = HangoutPlace::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'promo_text' => 'required|string|max:100',
        ]);

        $place->update([
            'promo_text' => $request->promo_text,
            'promo_expires_at' => now()->addHours(24) // Promo berlaku 24 jam
        ]);

        return back()->with('success', 'Promo berhasil diupdate!');
    }
}