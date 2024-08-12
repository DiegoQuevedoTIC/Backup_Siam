<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ParametrosGenerales;
use App\Filament\Resources\TipoEntidadResource\Pages;
use App\Filament\Resources\TipoEntidadResource\RelationManagers;
use App\Models\TipoEntidad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoEntidadResource extends Resource
{
    protected static ?string    $model = TipoEntidad::class;
    protected static ?string    $cluster = ParametrosGenerales::class;
    protected static ?string    $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string    $navigationLabel = 'Tipos de Entidades';
    protected static ?string    $navigationGroup = 'Parametros';
    protected static ?string    $navigationParentItem = 'Parametros Asociados';
    protected static ?string    $modelLabel = 'Tipo de Entidad';
    protected static ?string    $pluralModelLabel = 'Tipos de Entidades';
    protected static ?string    $slug = 'Par/Tab/TipEnt';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('nombre')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTipoEntidads::route('/'),
        ];
    }
}
