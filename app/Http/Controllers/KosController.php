<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class KosController extends Controller
{
    public function show($id)
    {
        $kos = Kos::with(['rooms', 'owner'])->findOrFail($id);
        return view('kos.show', compact('kos'));
    }

    public function rooms($id)
    {
        $kos = Kos::with('rooms')->findOrFail($id);
        return view('kos.rooms', compact('kos'));
    }

    public function index(Request $request)
    {
        // daftar kecamatan Palembang (bisa juga dipindah ke config atau helper)
        $palembangDistricts = [
        'Alang-alang Lebar','Bukit Kecil','Bukit Lama','Ilir Barat I','Ilir Barat II',
        'Ilir Timur I','Ilir Timur II','Kertapati','Kemuning','Sako',
        'Seberang Ulu I','Seberang Ulu II','Sukarami','Sungai Pinang','Sematang Borang',
        'Kalidoni','Plaju','Ilir Timur III'
        ];

        $query = Kos::with('photos','kamars','fasilitas','owner');

        // Batasi default ke PALembang
        $query->where('city','like','%Palembang%');

        // filter berdasarkan kecamatan (district) pilihan
        if ($request->filled('district')) {
            $district = $request->district;

            // jika kamu punya kolom 'district' di tabel kos:
            if (Schema::hasColumn('kos','district')) {
                $query->where('district', $district);
            } else {
                // fallback: cari kecamatan dalam kolom address
                $query->where('address','like', "%{$district}%");
            }
        }
    }
}