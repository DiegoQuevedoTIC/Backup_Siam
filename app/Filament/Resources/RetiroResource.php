<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Tesoreria;
use App\Filament\Resources\RetiroResource\Pages;
use App\Filament\Resources\RetiroResource\RelationManagers;
use App\Models\Retiro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RetiroResource extends Resource
{
    protected static ?string $model = Retiro::class;
    protected static ?string $cluster = Tesoreria::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-down';
    protected static?string $navigationLabel = 'Retiro saldo a favor';
    protected static?string $modelLabel = 'Retiro';

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
            'index' => Pages\ListRetiros::route('/'),
            'create' => Pages\CreateRetiro::route('/create'),
            'view' => Pages\ViewRetiro::route('/{record}'),
            'edit' => Pages\EditRetiro::route('/{record}/edit'),
        ];
    }
}
