<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1001Resource\Pages;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1001Resource\RelationManagers;
use App\Models\Exogena1001;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Exogena1001Resource extends Resource
{
    protected static ?string $model = Exogena1001::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1001 - Informacion de Terceros';
    protected static ?string $modelLabel = '1001 - Informacion de Terceros';


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
            'index' => Pages\ListExogena1001::route('/'),
            'create' => Pages\CreateExogena1001::route('/create'),
            'edit' => Pages\EditExogena1001::route('/{record}/edit'),
        ];
    }
}
