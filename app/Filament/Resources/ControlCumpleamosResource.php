<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SolidaridadBienestar;
use App\Filament\Resources\ControlCumpleamosResource\Pages;
use App\Filament\Resources\ControlCumpleamosResource\RelationManagers;
use App\Models\ControlCumpleamos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ControlCumpleamosResource extends Resource
{
    protected static ?string $model = ControlCumpleamos::class;
    protected static ?string $cluster = SolidaridadBienestar::class;
    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?string $navigationLabel = 'Control Cumpleaños';
    protected static ?string $modelLabel = 'Control Cumpleaños';

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
            'index' => Pages\ListControlCumpleamos::route('/'),
            'create' => Pages\CreateControlCumpleamos::route('/create'),
            'view' => Pages\ViewControlCumpleamos::route('/{record}'),
            'edit' => Pages\EditControlCumpleamos::route('/{record}/edit'),
        ];
    }
}
