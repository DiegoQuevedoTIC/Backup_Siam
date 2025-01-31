<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources;

use App\Filament\Clusters\SuperSolidaria;
use App\Filament\Clusters\SuperSolidaria\Resources\ErogacionesOrganosControlResource\Pages;
use App\Filament\Clusters\SuperSolidaria\Resources\ErogacionesOrganosControlResource\RelationManagers;
use App\Models\erogaciones_organos_control;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ErogacionesOrganosControlResource extends Resource
{
    protected static ?string $model = erogaciones_organos_control::class;
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
            'index' => Pages\ListErogacionesOrganosControls::route('/'),
            'create' => Pages\CreateErogacionesOrganosControl::route('/create'),
            'edit' => Pages\EditErogacionesOrganosControl::route('/{record}/edit'),
        ];
    }
}
