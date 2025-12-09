<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'kos_id',
        'room_number',
        'is_occupied',
        'price',
        'description',
    ];

    protected $casts = [
        'is_occupied' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Helper methods
    public function isAvailable()
    {
        return !$this->is_occupied;
    }

    public function getStatusColor()
    {
        return $this->is_occupied ? 'red' : 'green';
    }

    public function getStatusText()
    {
        return $this->is_occupied ? 'Terisi' : 'Kosong';
    }
}