<?php

namespace App\Filament\Resources\TerceroResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Tercero;
use App\Models\Parentesco;
use App\Models\Moneda;
use App\Models\Pais;
use Illuminate\Support\Collection;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;

class TerceroSarlaftRelationManager extends RelationManager
{
    protected static string $relationship = 'TerceroSarlaft';
    protected static ?string    $modelLabel = 'Informacion Sarlaft';
    protected static ?string    $pluralModelLabel = 'Informacion Sarlaft';
    protected static ?string    $slug = 'Par/Tab/InfSarl';

    public function form(Form $form): Form
    {
        return $form
            ->columns(5)
            ->schema([
                Toggle::make('reconocimiento_publico')
                        ->required()
                        ->markAsRequired(false)
                        ->columnSpan(2),
                TextInput::make('descripcion_reconocimiento')
                        ->markAsRequired(false)
                        ->autocomplete(false)
                        ->rule('regex:/^[a-zA-Z\s-]+$/')
                        ->columnSpan(3),
                Toggle::make('ejerce_cargos_publicos')
                        ->required()
                        ->markAsRequired(false)
                        ->columnSpan(2),
                TextInput::make('descripcion_cargo_publico')
                        ->markAsRequired(false)
                        ->autocomplete(false)
                        ->rule('regex:/^[a-zA-Z\s-]+$/')
                        ->columnSpan(3),
                Toggle::make('familiar_peps')
                        ->required()
                        ->markAsRequired(false)
                        ->columnSpan(2),
                Select::make('parentesco_id')
                        ->columnSpan(2)
                        ->placeholder('')
                        ->relationship('parentesco', 'nombre'),
                TextInput::make('peps_id')
                        ->markAsRequired(false)
                        ->columnSpan(1),
                Toggle::make('socio_peps')
                        ->required()
                        ->markAsRequired(false)
                        ->columnSpan(2),
                TextInput::make('nombre_peps')
                        ->autocomplete(false)
                        ->rule('regex:/^[a-zA-Z\s-]+$/')
                        ->markAsRequired(false)
                        ->columnSpan(3),
                Toggle::make('operacion_moneda_extranjera')
                        ->required()
                        ->markAsRequired(false)
                        ->columnSpan(2),
                Select::make('pais_id')
                        ->columnSpan(2)
                        ->placeholder('')
                        ->relationship('pais', 'nombre'),
                Select::make('moneda_id')
                        ->columnSpan(1)
                        ->placeholder('')
                        ->relationship('moneda', 'nombre'),
                TextInput::make('producto_moneda_extranjera')
                        ->columnSpan(2)
                        ->autocomplete(false)
                        ->markAsRequired(false),
                TextInput::make('tipo_producto_moneda_extranjera')
                        ->markAsRequired(false)
                        ->columnSpan(2),
                TextInput::make('monto_inicial')
                        ->markAsRequired(false)
                        ->maxLength(16)
                        ->columnSpan(2),
                TextInput::make('monto_final')
                        ->markAsRequired(false)
                        ->maxLength(16)
                        ->columnSpan(2),
                Toggle::make('declara_renta')
                        ->required()
                        ->label('Declarante Renta')
                        ->markAsRequired(false)
                        ->columnSpan(1),
                Textarea::make('origen_fondos')
                        ->maxLength(65535)
                        ->markAsRequired(false)
                        ->required()
                        ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tercero.tercero_id')
                    ->label('Identificacion'),
                TextColumn::make('tercero.nombres')
                    ->label('Nombres'),
                TextColumn::make('tercero.primer_apellido')
                    ->label('Primer Apellido'),
                TextColumn::make('tercero.segundo_apellido')
                    ->label('Segundo Apellido'),
                TextColumn::make('reconocimiento_publico')
                    ->label('Reconocimiento Publico'),
            ])
            ->filters([

            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Actualizar Informacion'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                ->label('+ Agregar Informacion Sarlaft'),
            ]);
    }
}
