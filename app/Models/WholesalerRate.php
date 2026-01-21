<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesalerRate extends Model
{
    protected $fillable = ['wholesaler_id', 'room_type_id', 'start_date', 'end_date', 'rate'];

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
