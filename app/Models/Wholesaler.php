<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wholesaler extends Model
{
    protected $fillable = ['name', 'contact_person', 'email', 'commission_rate'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
