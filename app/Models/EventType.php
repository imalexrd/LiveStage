<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function musicianProfiles()
    {
        return $this->belongsToMany(MusicianProfile::class, 'event_type_musician_profile');
    }
}
