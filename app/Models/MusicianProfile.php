<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MusicianProfile extends Model
{
    use HasFactory;

    // TODO: The creating event is not firing as expected when using the seeder.
    // The uuid is not being automatically generated. This needs further investigation.
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'manager_id',
        'artist_name',
        'bio',
        'location_city',
        'location_state',
        'base_price_per_hour',
        'banner_image_path',
        'latitude',
        'longitude',
        'is_approved',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_musician_profile');
    }

    public function eventTypes()
    {
        return $this->belongsToMany(EventType::class, 'event_type_musician_profile');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
