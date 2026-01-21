<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'guest_id', 'room_id', 'room_type_id', 'wholesaler_id',
        'check_in', 'check_out', 'total_price', 'status'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
