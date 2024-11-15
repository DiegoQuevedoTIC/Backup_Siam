<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SolidaridadBienestar;
use App\Filament\Resources\InformeBienestarResource\Pages;
use App\Filament\Resources\InformeBienestarResource\RelationManagers;
use App\Models\InformeBienestar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InformeBienestarResource extends Resource
{
    protected static ?string $model = InformeBienestar::class;
    protected static?string $cluster = SolidaridadBienestar::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static?string $navigationLabel = 'Informes de Bienestar';
    protected static?string $modelLabel = 'Informes de Bienestar';

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
            'index' => Pages\ListInformeBienestars::route('/'),
            'create' => Pages\CreateInformeBienestar::route('/create'),
            'view' => Pages\ViewInformeBienestar::route('/{record}'),
            'edit' => Pages\EditInformeBienestar::route('/{record}/edit'),
        ];
    }
}
