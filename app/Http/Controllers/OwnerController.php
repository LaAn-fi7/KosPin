<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class OwnerController extends Controller
{
    protected array $palembangDistricts;

    public function __construct()
    {
        // Baca daftar kecamatan dari config jika ada, kalau tidak gunakan fallback
        $this->palembangDistricts = Config::get('palembang.districts', [
            'Alang-alang Lebar','Bukit Kecil','Bukit Lama','Ilir Barat I','Ilir Barat II',
            'Ilir Timur I','Ilir Timur II','Kertapati','Kemuning','Sako',
            'Seberang Ulu I','Seberang Ulu II','Sukarami','Sungai Pinang','Sematang Borang',
            'Kalidoni','Plaju','Ilir Timur III'
        ]);
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
        // kirim daftar kecamatan ke view agar dropdown tersedia
        return view('owner.create-kos', [
            'palembangDistricts' => $this->palembangDistricts
        ]);
    }

    public function storeKos(Request $request)
    {
        // Validasi, termasuk district yang harus salah satu dari daftar (jika diisi)
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'price_per_month' => 'required|numeric|min:0',
            'gender' => 'required|in:male,female,mixed',
            'total_rooms' => 'required|integer|min:1|max:50',
            'district' => ['nullable','string','max:191', Rule::in($this->palembangDistricts)],
            // images validation
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $data = [
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'price_per_month' => $request->price_per_month,
            'gender' => $request->gender,
            'facilities' => $request->facilities ? array_values(array_filter(array_map('trim', explode(',', $request->facilities)))) : [],
            'total_rooms' => $request->total_rooms,
            'district' => $request->input('district'), // simpan district
        ];

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('photos/kos', $filename, 'public');
                $imagePaths[] = $path;
            }
        }
        $data['images'] = $imagePaths;

        $kos = Kos::create($data);

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

    /**
     * Update kos (edit) — menambah gambar baru, mempertahankan gambar lama
     */
    public function updateKos(Request $request, $kosId)
    {
        $kos = Kos::where('owner_id', Auth::id())->findOrFail($kosId);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'price_per_month' => 'required|numeric|min:0',
            'gender' => 'required|in:male,female,mixed',
            'total_rooms' => 'required|integer|min:1|max:50',
            'district' => ['nullable','string','max:191', Rule::in($this->palembangDistricts)],
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'price_per_month' => $request->price_per_month,
            'gender' => $request->gender,
            'facilities' => $request->facilities ? array_values(array_filter(array_map('trim', explode(',', $request->facilities)))) : $kos->facilities,
            'total_rooms' => $request->total_rooms,
            'district' => $request->input('district'),
        ];

        $existing = $kos->images ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('photos/kos', $filename, 'public');
                $existing[] = $path;
                // optional: enforce max images limit
                if (count($existing) >= 10) break;
            }
        }

        $data['images'] = $existing;
        $kos->update($data);

        // Optionally sync total_rooms vs Room records — left as-is
        return redirect()->back()->with('success', 'Kos berhasil diupdate.');
    }

    /**
     * Hapus satu gambar dari kos
     */
    public function removeImage(Request $request, $kosId)
    {
        $request->validate(['path' => 'required|string']);
        $kos = Kos::where('owner_id', Auth::id())->findOrFail($kosId);

        $pathToRemove = $request->input('path'); // e.g. photos/kos/abc.jpg
        $images = $kos->images ?? [];

        if (!in_array($pathToRemove, $images)) {
            return back()->with('error', 'Gambar tidak ditemukan.');
        }

        // hapus file fisik
        Storage::disk('public')->delete($pathToRemove);

        // hapus dari array dan simpan
        $images = array_values(array_diff($images, [$pathToRemove]));
        $kos->images = $images;
        $kos->save();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Hapus kos dan semua gambarnya
     */
    public function destroyKos($kosId)
    {
        $kos = Kos::where('owner_id', Auth::id())->findOrFail($kosId);

        foreach ($kos->images ?? [] as $p) {
            Storage::disk('public')->delete($p);
        }

        // hapus data kos (rooms/booking tergantung cascade)
        $kos->delete();

        return redirect()->route('owner.dashboard')->with('success', 'Kos dan semua gambar berhasil dihapus.');
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
    
}
