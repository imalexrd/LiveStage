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
        'event_location',
        'event_details',
        'status',
        'total_price',
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
