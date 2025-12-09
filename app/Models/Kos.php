<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;


class Kos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kos';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'address',
        'district',
        'city',
        'province',
        'price_per_month',
        'facilities',
        'images',
        'gender',
        'is_available',
        'latitude',
        'longitude',
        'total_rooms'
    ];

    protected $casts = [
        'facilities' => 'array',
        'images' => 'array',
        'is_available' => 'boolean',
        'price_per_month' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Helper methods
    public function getAvailableRoomsCount()
    {
        return $this->rooms()->where('is_occupied', false)->count();
    }

    public function getTotalRoomsCount()
    {
        return $this->rooms()->count();
    }

    /**
     * Kembalikan path file utama atau default.
     * NOTE: hanya path relatif (mis: photos/kos/abc.jpg) jika kamu butuh itu.
     */
    public function getMainImagePath()
    {
        return $this->images && count($this->images) ? $this->images[0] : null;
    }

    /**
     * Kembalikan URL penuh untuk main image (Storage::url).
     * Jika tidak ada, kembalikan URL ke gambar default (pastikan file default ada di public/images).
     */
    public function getMainImageAttribute()
    {
        $path = $this->getMainImagePath();
        if ($path) {
            return Storage::url($path);
        }
        return asset('images/default-kos.jpg'); // sesuaikan lokasi default
    }

    /**
     * Accessor: kembalikan array URL siap pakai untuk semua gambar
     * Gunakan di blade: $kos->image_urls
     */
    public function getImageUrlsAttribute()
    {
        $images = $this->images ?? [];
        return collect($images)->map(function($p){
            return Storage::url($p);
        })->toArray();
    }
}
