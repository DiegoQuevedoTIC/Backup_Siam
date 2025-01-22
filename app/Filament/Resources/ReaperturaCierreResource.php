<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ProcesosContabilidad;
use App\Filament\Resources\ReaperturaCierreResource\Pages;
use App\Filament\Resources\ReaperturaCierreResource\RelationManagers;
use App\Models\ReaperturaCierre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReaperturaCierreResource extends Resource
{
    protected static ?string $model = ReaperturaCierre::class;

    protected static ?string    $cluster = ProcesosContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string    $navigationLabel = 'Reapertura Cierre';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('amo')
                    ->label('Año a reabrir')
                    ->regex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->length(4)
                    ->autocomplete(false)
                    ->required(),
                Forms\Components\Select::make('mes')
                    ->label('Mes a reaperturar')
                    ->native(false)
                    ->searchable()
                    ->options([
                        '1' => 'Enero',
                        '2' => 'Febrero',
                        '3' => 'Marzo',
                        '4' => 'Abril',
                        '5' => 'Mayo',
                        '6' => 'Junio',
                        '7' => 'Julio',
                        '8' => 'Agosto',
                        '9' => 'Septiembre',
                        '10' => 'Octubre',
                        '11' => 'Noviembre',
                        '12' => 'Diciembre',
                    ])
                    ->loadingMessage('Cargando...')
                    ->noSearchResultsMessage('No hay resultados.')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('amo')
                    ->label('Año')
                    ->sortable(),
                Tables\Columns\TextColumn::make('mes')
                    ->label('Mes')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha creación')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Creado por')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListReaperturaCierres::route('/'),
            'create' => Pages\CreateReaperturaCierre::route('/create'),
            'edit' => Pages\EditReaperturaCierre::route('/{record}/edit'),
        ];
    }
}
