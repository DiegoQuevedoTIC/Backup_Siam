<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ParametrosCartera;
use App\Filament\Resources\CreditoLineaResource\Pages;
use App\Filament\Resources\CreditoLineaResource\RelationManagers;
use App\Models\ClasificacionCredito;
use App\Models\CreditoLinea;
use App\Models\Moneda;
use App\Models\TipoGarantia;
use App\Models\TipoInversion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditoLineaResource extends Resource
{
    protected static ?string $model = CreditoLinea::class;
    protected static ?string $cluster = ParametrosCartera::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Lineas de credito';
    protected static ?string $modelLabel = 'Linea de credito';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descripcion')->label('Descripcion')
                    ->required(),
                Forms\Components\Select::make('clasificacion')->label('Clasificacion')
                    ->options(ClasificacionCredito::all()->pluck('descripcion', 'id'))
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->required(),
                        Forms\Components\TextInput::make('clasificacion')
                            ->required(),
                        Forms\Components\Textarea::make('descripcion')
                            ->required()
                            ->columns(2)
                    ])
                    ->createOptionUsing(function (array $data) {
                        $ClasificacionCredito = ClasificacionCredito::create($data);
                        return $ClasificacionCredito->id;
                    }),
                Forms\Components\Select::make('tipo_garantia')->label('Tipo de garantia')
                    ->options(TipoGarantia::all()->pluck('nombre', 'id'))
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->required(),
                        Forms\Components\TextInput::make('clasificacion')
                            ->required(),
                        Forms\Components\Textarea::make('descripcion')
                            ->required()
                    ])->createOptionUsing(function (array $data) {
                        $tipoGarantia = TipoGarantia::create($data);
                        return $tipoGarantia->id;
                    }),
                Forms\Components\Select::make('tipo_inversion')->label('Tipo de inversion')
                    ->options(TipoInversion::all()->pluck('tipo_inversion', 'id'))
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('tipo_inversion')
                            ->required(),
                        Forms\Components\Textarea::make('descripcion')
                            ->required()
                    ])->createOptionUsing(function (array $data) {
                        $TipoInversion = TipoInversion::create($data);
                        return $TipoInversion->id;
                    }),
                Forms\Components\Select::make('moneda')->label('Moneda')
                    ->options(Moneda::all()->pluck('nombre', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('periodo_pago')->label('Periodo de pago')
                    ->options([
                        'quincenal' => 'Quincenal',
                        'mensual' => 'Mensual',
                        'trimestral' => 'Trimestral',
                        'semestral' => 'Semestral',
                        'anual' => 'Anual',
                        'cuatrimestral' => 'Cuatrimestral',
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('interes_cte')->label('Interes cte'),
                Forms\Components\TextInput::make('interes_mora')->label('Interes mora'),
                Forms\Components\Select::make('tipo_cuota')->label('Tipo de cuota')
                    ->options([
                        'fija' => 'Fija',
                        'variable' => 'Variable',
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('tipo_tasa')->label('Tipo de tasa')
                    ->options([
                        'anticipada' => 'Anticipada',
                        'vencida' => 'Vencida',
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('nro_cuotas_max')->label('Nro cuotas max')->numeric(),
                Forms\Components\TextInput::make('nro_cuotas_gracia')->label('Nro cuotas gracia')->numeric(),
                Forms\Components\TextInput::make('cant_gar_real')->label('Cantidad garantia real')->numeric(),
                Forms\Components\TextInput::make('cant_gar_pers')->label('Cantidad garantia pers')->numeric(),
                Forms\Components\TextInput::make('monto_min')->label('Monto min')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->prefix('$')
                    ->default(0.00),
                Forms\Components\TextInput::make('monto_max')->label('Monto max')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->prefix('$')
                    ->default(0.00),
                Forms\Components\Select::make('abonos_extra')->label('Abonos extra')->options([]),
                Forms\Components\TextInput::make('ciius')->label('Ciiu'),
                Forms\Components\TextInput::make('subcentro')->label('Subcentro'),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('descripcion'),
                Tables\Columns\TextColumn::make('clasificacion'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
