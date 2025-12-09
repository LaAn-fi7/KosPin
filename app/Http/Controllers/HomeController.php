<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    // Daftar kecamatan / wilayah Palembang (sesuaikan bila perlu)
    protected array $palembangDistricts = [
        'Alang-alang Lebar','Bukit Kecil','Bukit Lama','Ilir Barat I','Ilir Barat II',
        'Ilir Timur I','Ilir Timur II','Kertapati','Kemuning','Sako',
        'Seberang Ulu I','Seberang Ulu II','Sukarami','Sungai Pinang','Sematang Borang',
        'Kalidoni','Plaju','Ilir Timur III'
    ];

    public function index(Request $request)
    {
        // Featured kos: yang tersedia dan mempunyai kamar kosong
        $featuredKos = Kos::with(['rooms', 'owner'])
            ->where('is_available', true)
            ->whereHas('rooms', function($query) {
                $query->where('is_occupied', false);
            })
            ->limit(6)
            ->get();

        // Jika view home/index butuh dropdown kecamatan, kirim juga variabelnya
        return view('home.index', [
            'featuredKos' => $featuredKos,
            'palembangDistricts' => $this->palembangDistricts,
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->query('q');
        $district = $request->query('district'); // kecamatan dari dropdown
        $min = $request->query('min_price');
        $max = $request->query('max_price');
        $gender = $request->query('gender');
        $availableOnly = $request->filled('available_only');

        $query = Kos::with(['rooms', 'owner'])
            // default: batasi ke kota Palembang (supaya tidak seluruh Indonesia)
            ->where('city', 'like', '%Palembang%');

        // Jika ada query pencarian umum (judul, deskripsi)
        if ($request->filled('q')) {
            $query->where(function($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('name', 'like', "%{$q}%") // fallback nama kolom
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter district/kecamatan (jika dropdown dipilih)
        if ($district) {
            if (Schema::hasColumn('kos', 'district')) {
                $query->where('district', $district);
            } else {
                // fallback: cari di kolom address
                $query->where('address', 'like', "%{$district}%");
            }
        }

        // Filter price
        if ($min) {
            $query->where('price_per_month', '>=', $min);
        }
        if ($max) {
            $query->where('price_per_month', '<=', $max);
        }

        // Filter gender/tipe (sesuaikan nama kolom di DB)
        if ($gender) {
            // beberapa project menyimpan kolom 'gender' atau 'tipe'
            if (Schema::hasColumn('kos', 'gender')) {
                $query->where('gender', $gender);
            } elseif (Schema::hasColumn('kos', 'tipe')) {
                $query->where('tipe', $gender);
            }
        }

        // Filter hanya yang memiliki kamar kosong
        if ($availableOnly) {
            $query->whereHas('rooms', function($q) {
                $q->where('is_occupied', false);
            });
        }

        $kos = $query->paginate(12)->withQueryString();

        return view('home.search', [
            'kos' => $kos,
            'palembangDistricts' => $this->palembangDistricts,
            'q' => $q,
        ]);
    }
}
