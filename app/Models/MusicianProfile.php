<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MusicianProfile extends Model
{
    use HasFactory;

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
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
