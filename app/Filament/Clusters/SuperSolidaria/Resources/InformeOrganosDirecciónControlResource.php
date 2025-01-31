<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources;

use App\Filament\Clusters\SuperSolidaria;
use App\Filament\Clusters\SuperSolidaria\Resources\InformeOrganosDirecciónControlResource\Pages;
use App\Filament\Clusters\SuperSolidaria\Resources\InformeOrganosDirecciónControlResource\RelationManagers;
use App\Models\informe_organos_dirección_control;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InformeOrganosDirecciónControlResource extends Resource
{
    protected static ?string $model = informe_organos_dirección_control::class;
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
            'index' => Pages\ListInformeOrganosDirecciónControls::route('/'),
            'create' => Pages\CreateInformeOrganosDirecciónControl::route('/create'),
            'edit' => Pages\EditInformeOrganosDirecciónControl::route('/{record}/edit'),
        ];
    }
}
