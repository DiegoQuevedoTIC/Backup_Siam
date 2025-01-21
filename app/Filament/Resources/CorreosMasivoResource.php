<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ComunicacionExterna;
use App\Filament\Resources\CorreosMasivoResource\Pages;
use App\Filament\Resources\CorreosMasivoResource\RelationManagers;
use App\Models\CorreosMasivo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CorreosMasivoResource extends Resource
{
    protected static ?string $model = CorreosMasivo::class;

    protected static ?string $cluster = ComunicacionExterna::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Correo Electronico Masivo';
    protected static ?string $modelLabel = 'Correo Electronico Masivo';

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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCorreosMasivos::route('/'),
            'create' => Pages\CreateCorreosMasivo::route('/create'),
            'view' => Pages\ViewCorreosMasivo::route('/{record}'),
            'edit' => Pages\EditCorreosMasivo::route('/{record}/edit'),
        ];
    }
}
