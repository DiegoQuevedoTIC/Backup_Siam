<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ParametrosAsociados;
use App\Filament\Resources\TipoContratoResource\Pages;
use App\Filament\Resources\TipoContratoResource\RelationManagers;
use App\Models\TipoContrato;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoContratoResource extends Resource
{
    protected static ?string     $model = TipoContrato::class;
    protected static ?string    $cluster = ParametrosAsociados::class;
    protected static ?string    $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string    $navigationLabel = 'Tipos de Contratos';
    protected static ?string    $navigationGroup = 'Parametros';
    protected static ?string    $navigationParentItem = 'Parametros Asociados';
    protected static ?string    $modelLabel = 'Tipo de  Contrato';
    protected static ?string    $pluralModelLabel = 'Tipos de Contratos';
    protected static ?string    $slug = 'Par/Tab/TipContr';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->autocomplete(false)
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
            'index' => Pages\ManageTipoContratos::route('/'),
        ];
    }
}
