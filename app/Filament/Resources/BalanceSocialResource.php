<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SolidaridadBienestar;
use App\Filament\Resources\BalanceSocialResource\Pages;
use App\Filament\Resources\BalanceSocialResource\RelationManagers;
use App\Models\BalanceSocial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BalanceSocialResource extends Resource
{
    protected static ?string $model = BalanceSocial::class;
    protected static?string $cluster = SolidaridadBienestar::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static?string $navigationLabel = 'Balances Social';
    protected static?string $modelLabel = 'Balances Social';

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
            'index' => Pages\ListBalanceSocials::route('/'),
            'create' => Pages\CreateBalanceSocial::route('/create'),
            'view' => Pages\ViewBalanceSocial::route('/{record}'),
            'edit' => Pages\EditBalanceSocial::route('/{record}/edit'),
        ];
    }
}
