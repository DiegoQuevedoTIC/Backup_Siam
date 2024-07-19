<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditoLineaResource\Pages;
use App\Filament\Resources\CreditoLineaResource\RelationManagers;
use App\Models\CreditoLinea;
use App\Models\Tasa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditoLineaResource extends Resource
{
    protected static ?string $model = CreditoLinea::class;

        protected static ?string    $navigationIcon = 'heroicon-o-globe-alt';
        protected static ?string    $navigationLabel = 'Lineas de Credito';
        protected static ?string    $navigationGroup = 'Parametros';
        protected static ?string    $navigationParentItem = 'Parametros Terceros';
        protected static ?string    $modelLabel = 'Linea de Credito';
        protected static ?string    $pluralModelLabel = 'Lineas de Credito';
        protected static ?string    $slug = 'Par/Tab/LinCred';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
            TextInput::make('linea')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(99)
                        ->maxLength(2)
                        ->unique(ignoreRecord: true)
                        ->disabled(fn ($record) => optional($record)->exists ?? false)
                        ->label('No de la Linea')
                        ->columnSpan(1),
            TextInput::make('descripcion')
                        ->required()
                        ->maxLength(120)
                        ->label('Nombre de la linea de credito')
                        ->columnSpan(7),
            Select::make('clasificacion')
                        ->relationship('clasificacion', 'clasificacion')
                        ->searchable()
                        ->preload()
                        ->columnSpan(2)
                        ->createOptionForm([
                            TextInput::make('clasificacion')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(2),
                            TextInput::make('descripcion')
                                ->required()
                                ->label('Descripcion del Tipo de Clasificacion')
                                ->maxLength(255)
                                ->columnSpan(2),
                        ]),
            Select::make('tipo_garantia')
                        ->relationship('tipoGarantia', 'clasificacion')
                        ->searchable()
                        ->preload()
                        ->columnSpan(2)
                        ->createOptionForm([
                            TextInput::make('clasificacion')
                                ->required()
                                ->label('Tipo de Garantia')
                                ->maxLength(255)
                                ->columnSpan(2),
                            TextInput::make('descripcion')
                                ->required()
                                ->maxLength(255)
                                ->label('Descripcion del Tipo de Garantia')
                                ->columnSpan(2),
                        ]),
            Select::make('tipo_inversion')
                        ->relationship('tipoInversion', 'tipo_inversion')
                        ->searchable()
                        ->preload()
                        ->columnSpan(2)
                        ->createOptionForm([
                            TextInput::make('tipo_inversion')
                                ->required()
                                ->label('Tipo de Inversion')
                                ->maxLength(255)
                                ->columnSpan(2),
                            TextInput::make('descripcion')
                                ->required()
                                ->maxLength(255)
                                ->label('Descripcion del Tipo de Inversion')
                                ->columnSpan(2),
                        ]),
            Select::make('moneda')
                        ->relationship('moneda', 'nombre')
                        ->required()
                        ->markAsRequired(false)
                        ->preload()
                        ->columnSpan(2)
                        ->label('Moneda'),
            TextInput::make('periodo_pago')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->columnSpan(2)
                        ->maxValue(360)
                        ->maxLength(2),
            Select::make('interes_cte')
                        ->label('Tasa Corriente')
                        ->required()
                        ->columnSpan(1)
                        ->placeholder('% EA')
                        ->options(fn () => Tasa::query()->pluck('nombre', 'id'))
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('nombre')
                                ->label('Nombre de la tasa')
                                ->required()
                                ->columns(2),
                            TextInput::make('tasa')
                                ->label('Valor de la tasa')
                                ->numeric()
                                ->required()
                                ->columns(2),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        return Tasa::create($data)->id;
                                    }),
            Select::make('interes_mora')
                        ->label('Tasa Mora')
                        ->required()
                        ->placeholder('% EA')
                        ->options(fn () => Tasa::query()->pluck('nombre', 'id'))
                        ->searchable()
                        ->columnSpan(1)
                        ->createOptionForm([
                            TextInput::make('nombre')
                                ->label('Nombre de la tasa')
                                ->required()
                                ->columns(1),
                            TextInput::make('tasa')
                                ->label('Valor de la tasa')
                                ->numeric()
                                ->required()
                                ->columns(1),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        return Tasa::create($data)->id;
                                    }),
            Select::make('tipo_cuota')
                        ->placeholder('')
                        ->options([
                                    'F' => 'Fija',
                                    'V' => 'Variable',
                                    'C' => 'Cerrada',
                                ])
                        ->columnSpan(2)
                        ->required(),
            Select::make('tipo_tasa')
                        ->placeholder('')
                        ->options([
                                    'A' => 'Anticipada',
                                    'V' => 'Vencida',
                                ])
                        ->columnSpan(2)
                        ->required(),
            TextInput::make('nro_cuotas_max')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(99)
                        ->maxLength(2)
                        ->label('Cuotas Maximo')
                        ->columnSpan(2),
            TextInput::make('nro_cuotas_gracia')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(99)
                        ->maxLength(2)
                        ->label('Cuotas de Gracias')
                        ->columnSpan(2),
            TextInput::make('cant_gar_real')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(99)
                        ->maxLength(2)
                        ->label('No Garantias Reales')
                        ->columnSpan(2),
            TextInput::make('cant_gar_pers')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(99)
                        ->maxLength(2)
                        ->label('No Garantias Pers')
                        ->columnSpan(2),
            TextInput::make('monto_min')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(999999999)
                        ->maxLength(2)
                        ->label('Monto Minimo de Credito')
                        ->columnSpan(4),
            TextInput::make('monto_max')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(99999999)
                        ->maxLength(2)
                        ->label('Monto Maximo de Credito')
                        ->columnSpan(4),
            Select::make('abonos_extra')
                        ->placeholder('Permite Cuotas Extra')
                        ->options([
                                    'S' => 'Si',
                                    'N' => 'No',
                                ])
                        ->columnSpan(2)
                        ->required(),
            TextInput::make('ciius')
                        ->required()
                        ->maxLength(4)
                        ->columnSpan(2),
            TextInput::make('subcentro')
                        ->required()
                        ->maxLength(3)
                        ->columnSpan(2),
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
            'index' => Pages\ListCreditoLineas::route('/'),
            'create' => Pages\CreateCreditoLinea::route('/create'),
            'edit' => Pages\EditCreditoLinea::route('/{record}/edit'),
        ];
    }
}
