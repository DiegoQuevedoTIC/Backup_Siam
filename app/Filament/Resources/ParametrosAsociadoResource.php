<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParametrosAsociadoResource\Pages;
use App\Filament\Resources\ParametrosAsociadoResource\RelationManagers;
use App\Models\ParametrosAsociado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PagaduriaResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParametrosAsociadoResource extends Resource
{
    protected static ?string $model = ParametrosAsociado::class;

    protected static ?string    $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string    $navigationLabel = 'Parametros Asociados';
    protected static ?string    $navigationGroup = 'Parametros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([

            ])
            ->filters([

            ])
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParametrosAsociados::route('/'),
            'view' => Pages\ViewParametrosAsociado::route('/{record}'),
            'edit' => Pages\EditParametrosAsociado::route('/{record}/edit'),
        ];
    }
}
