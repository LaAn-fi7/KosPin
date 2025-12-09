<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    use HasFactory;

    protected $table = 'kos';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'address',
        'city',
        'province',
        'price_per_month',
        'facilities',
        'images',
        'gender',
        'is_available',
        'latitude',
        'longitude',
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

    public function getMainImage()
    {
        return $this->images ? $this->images[0] : 'default-kos.jpg';
    }
}