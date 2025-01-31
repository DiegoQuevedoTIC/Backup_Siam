<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources;

use App\Filament\Clusters\SuperSolidaria;
use App\Filament\Clusters\SuperSolidaria\Resources\AsociadosEmpleadosDeudoresVentasBienesResource\Pages;
use App\Filament\Clusters\SuperSolidaria\Resources\AsociadosEmpleadosDeudoresVentasBienesResource\RelationManagers;
use App\Models\asociados_empleados_deudores_ventas_bienes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsociadosEmpleadosDeudoresVentasBienesResource extends Resource
{
    protected static ?string $model = asociados_empleados_deudores_ventas_bienes::class;
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
            'index' => Pages\ListAsociadosEmpleadosDeudoresVentasBienes::route('/'),
            'create' => Pages\CreateAsociadosEmpleadosDeudoresVentasBienes::route('/create'),
            'edit' => Pages\EditAsociadosEmpleadosDeudoresVentasBienes::route('/{record}/edit'),
        ];
    }
}
