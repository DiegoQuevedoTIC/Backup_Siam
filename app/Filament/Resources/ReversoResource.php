<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Tesoreria;
use App\Filament\Resources\ReversoResource\Pages;
use App\Filament\Resources\ReversoResource\RelationManagers;
use App\Models\Reverso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReversoResource extends Resource
{
    protected static ?string $model = Reverso::class;
    protected static ?string $cluster = Tesoreria::class;
    protected static ?string $navigationIcon = 'heroicon-m-arrow-path';
    protected static ?string $navigationLabel = 'Reverso de transacciones';
    protected static ?string $modelLabel = 'Reversos';
    protected static ?int $navigationSort = 4;

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
            'index' => Pages\ListReversos::route('/'),
            'create' => Pages\CreateReverso::route('/create'),
            'view' => Pages\ViewReverso::route('/{record}'),
            'edit' => Pages\EditReverso::route('/{record}/edit'),
        ];
    }
}
