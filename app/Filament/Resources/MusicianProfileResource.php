<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MusicianProfileResource\Pages;
use App\Filament\Resources\MusicianProfileResource\RelationManagers;
use App\Models\MusicianProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MusicianProfileResource extends Resource
{
    protected static ?string $model = MusicianProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('artist_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('bio')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('location_city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('location_state')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('base_price_per_hour')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_approved')
                    ->required(),
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Manager')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('artist_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_state')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->action(fn (MusicianProfile $record) => $record->update(['is_approved' => true]))
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'create' => Pages\CreateMusicianProfile::route('/create'),
            'edit' => Pages\EditMusicianProfile::route('/{record}/edit'),
        ];
    }
}
