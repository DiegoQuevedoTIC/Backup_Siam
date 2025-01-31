<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources;

use App\Filament\Clusters\SuperSolidaria;
use App\Filament\Clusters\SuperSolidaria\Resources\EvaluacionRiesgoLiquidezResource\Pages;
use App\Filament\Clusters\SuperSolidaria\Resources\EvaluacionRiesgoLiquidezResource\RelationManagers;
use App\Models\evaluacion_riesgo_liquidez;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EvaluacionRiesgoLiquidezResource extends Resource
{
    protected static ?string $model = evaluacion_riesgo_liquidez::class;
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
            'index' => Pages\ListEvaluacionRiesgoLiquidezs::route('/'),
            'create' => Pages\CreateEvaluacionRiesgoLiquidez::route('/create'),
            'edit' => Pages\EditEvaluacionRiesgoLiquidez::route('/{record}/edit'),
        ];
    }
}
