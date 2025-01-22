<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ParametrosAsociados;
use App\Filament\Resources\NovedadTerceroResource\Pages;
use App\Filament\Resources\NovedadTerceroResource\RelationManagers;
use App\Models\NovedadTercero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\EstadoCliente;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NovedadTerceroResource extends Resource
{
    protected static ?string    $model = NovedadTercero::class;
    protected static ?string    $cluster = ParametrosAsociados::class;
    protected static ?string    $navigationIcon = 'heroicon-o-building-library';
    protected static ?string    $navigationLabel = 'Novedades de Terceros';
    protected static ?string    $navigationGroup = 'Parametros';
    protected static ?string    $navigationParentItem = 'Parametros Asociados';
    protected static ?string    $modelLabel = 'Tercero Novedad';
    protected static ?string    $pluralModelLabel = 'Novedades de Terceros';
    protected static ?string    $slug = 'Par/Tab/NovTerc';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('codigo')
                ->required()
                ->autocomplete(false)
                ->unique(ignoreRecord: true)
                ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                ->maxLength(3),
            TextInput::make('nombre')
                ->required()
                ->autocomplete(false)
                ->maxLength(255),
            Textarea::make('descripcion')
                ->maxLength(65535)
                ->autocomplete(false)
                ->markAsRequired(false)
                ->columnSpanFull(),
            Toggle::make('cambiaestado')
                ->required(),
            Select::make('estado_cliente_id')
                ->relationship('EstadoCliente', 'nombre')
                ->required()
                ->markAsRequired(false)
                ->preload()
                ->label('Estado del Cliente'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                ->searchable(),
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

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNovedadTerceros::route('/'),
            'create' => Pages\CreateNovedadTercero::route('/create'),
            'edit' => Pages\EditNovedadTercero::route('/{record}/edit'),
        ];
    }
}
