<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources;

use App\Filament\Clusters\SuperSolidaria;
use App\Filament\Clusters\SuperSolidaria\Resources\RelacionPropiedadPlantaEquipoResource\Pages;
use App\Filament\Clusters\SuperSolidaria\Resources\RelacionPropiedadPlantaEquipoResource\RelationManagers;
use App\Models\relacion_propiedad_planta_equipo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RelacionPropiedadPlantaEquipoResource extends Resource
{
    protected static ?string $model = relacion_propiedad_planta_equipo::class;
    protected static ?string $cluster = SuperSolidaria::class;

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
            'index' => Pages\ListRelacionPropiedadPlantaEquipos::route('/'),
            'create' => Pages\CreateRelacionPropiedadPlantaEquipo::route('/create'),
            'edit' => Pages\EditRelacionPropiedadPlantaEquipo::route('/{record}/edit'),
        ];
    }
}
