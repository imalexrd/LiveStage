<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
