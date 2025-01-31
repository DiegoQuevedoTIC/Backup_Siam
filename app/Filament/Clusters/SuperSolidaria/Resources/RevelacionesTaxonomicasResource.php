<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources;

use App\Filament\Clusters\SuperSolidaria;
use App\Filament\Clusters\SuperSolidaria\Resources\RevelacionesTaxonomicasResource\Pages;
use App\Filament\Clusters\SuperSolidaria\Resources\RevelacionesTaxonomicasResource\RelationManagers;
use App\Models\revelaciones_taxonomicas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RevelacionesTaxonomicasResource extends Resource
{
    protected static ?string $model = revelaciones_taxonomicas::class;
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
            'index' => Pages\ListRevelacionesTaxonomicas::route('/'),
            'create' => Pages\CreateRevelacionesTaxonomicas::route('/create'),
            'edit' => Pages\EditRevelacionesTaxonomicas::route('/{record}/edit'),
        ];
    }
}
