<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SolidaridadBienestar;
use App\Filament\Resources\InscripcionActividadesResource\Pages;
use App\Filament\Resources\InscripcionActividadesResource\RelationManagers;
use App\Models\InscripcionActividades;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InscripcionActividadesResource extends Resource
{
    protected static ?string $model = InscripcionActividades::class;
    protected static?string $cluster = SolidaridadBienestar::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static?string $navigationLabel = 'Inscripciones Actividades';
    protected static?string $modelLabel = 'Inscripciones Actividades';

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
            'index' => Pages\ListInscripcionActividades::route('/'),
            'create' => Pages\CreateInscripcionActividades::route('/create'),
            'view' => Pages\ViewInscripcionActividades::route('/{record}'),
            'edit' => Pages\EditInscripcionActividades::route('/{record}/edit'),
        ];
    }
}
