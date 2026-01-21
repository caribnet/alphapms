<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['invoice_id', 'amount', 'payment_method', 'paid_at'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
