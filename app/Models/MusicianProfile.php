<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicianProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'artist_name',
        'bio',
        'location_city',
        'location_state',
        'base_price_per_hour',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
