<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MusicianProfileResource\Pages;
use App\Models\MusicianProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class MusicianProfileResource extends Resource
{
    protected static ?string $model = MusicianProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Manager Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('artist_name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('approve')
                    ->action(function (MusicianProfile $record) {
                        $record->is_approved = true;
                        $record->save();
                        // TODO: Add email notification to the manager
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->visible(fn (MusicianProfile $record) => !$record->is_approved),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMusicianProfiles::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
