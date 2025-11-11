<?php

namespace App\Filament\Resources\MusicianProfileResource\Pages;

use App\Filament\Resources\MusicianProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMusicianProfiles extends ListRecords
{
    protected static string $resource = MusicianProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
