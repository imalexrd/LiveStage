<?php

namespace App\Filament\Resources\MusicianProfileResource\Pages;

use App\Filament\Resources\MusicianProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMusicianProfile extends CreateRecord
{
    protected static string $resource = MusicianProfileResource::class;
}
