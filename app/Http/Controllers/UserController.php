<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function myBookings()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $bookings = Booking::with(['kos', 'room', 'kos.owner'])
                          ->where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('user.my-bookings', compact('bookings'));
    }

    public function cancelBooking($id)
    {
        $booking = Booking::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->first();

        if (!$booking) {
            return back()->with('error', 'Booking tidak ditemukan');
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking tidak dapat dibatalkan');
        }

        $booking->update(['status' => 'cancelled']);
        $booking->room->update(['is_occupied' => false]);

        return back()->with('success', 'Booking berhasil dibatalkan');
    }
}