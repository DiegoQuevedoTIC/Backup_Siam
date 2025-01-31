<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource\Pages;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource\RelationManagers;
use App\Models\Exogena1008;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Exogena1008Resource extends Resource
{
    protected static ?string $model = Exogena1008::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1008 - Saldos de Cuentas por Cobrar';
    protected static ?string $modelLabel = '1008 - Saldos de Cuentas por Cobrar';

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
            'index' => Pages\ListExogena1008s::route('/'),
            'create' => Pages\CreateExogena1008::route('/create'),
            'edit' => Pages\EditExogena1008::route('/{record}/edit'),
        ];
    }
}
