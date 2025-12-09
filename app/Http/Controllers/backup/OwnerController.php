<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class OwnerController extends Controller
{
    public function __construct()
    {
        
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user->isOwner()) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $kos = Kos::where('owner_id', $user->id)
                  ->with('rooms')
                  ->get();

        $stats = [
            'total_kos' => $kos->count(),
            'total_rooms' => $kos->sum(function($k) { return $k->rooms->count(); }),
            'occupied_rooms' => $kos->sum(function($k) { 
                return $k->rooms->where('is_occupied', true)->count(); 
            }),
            'available_rooms' => $kos->sum(function($k) { 
                return $k->rooms->where('is_occupied', false)->count(); 
            })
        ];

        return view('owner.dashboard', compact('kos', 'stats'));
    }

    public function createKos()
    {
        return view('owner.create-kos');
    }

    public function storeKos(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'price_per_month' => 'required|numeric|min:0',
            'gender' => 'required|in:male,female,mixed',
            'total_rooms' => 'required|integer|min:1|max:50'
        ]);

        $kos = Kos::create([
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'price_per_month' => $request->price_per_month,
            'gender' => $request->gender,
            'facilities' => $request->facilities ? explode(',', $request->facilities) : [],
        ]);

        // Create rooms
        for ($i = 1; $i <= $request->total_rooms; $i++) {
            Room::create([
                'kos_id' => $kos->id,
                'room_number' => sprintf('%02d', $i),
                'is_occupied' => false,
                'price' => $request->price_per_month,
            ]);
        }

        return redirect()->route('owner.dashboard')->with('success', 'Kos berhasil dibuat!');
    }

    public function manageRooms($kosId)
    {
        $kos = Kos::with('rooms')->where('owner_id', Auth::id())->findOrFail($kosId);
        return view('owner.manage-rooms', compact('kos'));
    }

    public function updateRoomStatus(Request $request, $roomId)
    {
        $room = Room::whereHas('kos', function($query) {
            $query->where('owner_id', Auth::id());
        })->findOrFail($roomId);

        $room->update([
            'is_occupied' => !$room->is_occupied
        ]);

        return response()->json([
            'success' => true,
            'status' => $room->is_occupied,
            'message' => 'Status kamar berhasil diupdate'
        ]);
    }
    
    public function confirmBooking($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Pastikan hanya owner kos yang bersangkutan yang bisa konfirmasi
        if(auth()->id() != $booking->kos->owner_id){
            return back()->with('error', 'Tidak memiliki akses konfirmasi!');
        }
        
        // Ubah status booking jadi confirmed
        $booking->update(['status' => 'confirmed']);
        
        return back()->with('success', 'Booking berhasil dikonfirmasi!');
    }


    public function listBookings()
    {
        $bookings = Booking::with(['user', 'kos', 'room'])
            ->whereHas('kos', function($q) {
                $q->where('owner_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('owner.bookings', compact('bookings'));
    }
    
}