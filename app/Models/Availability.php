<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    /** @use HasFactory<\Database\Factories\AvailabilityFactory> */
    use HasFactory;

    protected $fillable = [
        'musician_profile_id',
        'unavailable_date',
        'reason',
    ];

    protected $casts = [
        'unavailable_date' => 'date',
    ];

    public function musicianProfile()
    {
        return $this->belongsTo(MusicianProfile::class);
    }
}
