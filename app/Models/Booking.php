<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'musician_profile_id',
        'event_date',
        'location_address',
        'location_latitude',
        'location_longitude',
        'event_details',
        'status',
        'total_price',
        'app_fee',
        'urgency_fee',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function musicianProfile()
    {
        return $this->belongsTo(MusicianProfile::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
