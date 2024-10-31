<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ProcesosContabilidad;
use App\Filament\Resources\ComprobanteResource\Pages;
use App\Models\Comprobante;
use App\Models\Puc;
use App\Models\Tercero;
use App\Models\TipoContribuyente;
use App\Models\TipoDocumentoContable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Facades\Route;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\RawJs;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;


class ComprobanteResource extends Resource
{
    protected static ?string    $model = Comprobante::class;
    protected static ?string    $cluster = ProcesosContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-calculator';
    protected static ?string    $navigationLabel = 'Creacion Comprobantes';
    protected static ?string    $modelLabel = 'Comprobante Contable';

    public static function form(Form $form): Form
    {
        $query = TipoDocumentoContable::all()->toArray();
        $tipoDocumento = array();
        foreach ($query as $row) {
            $tipoDocumento[$row['id']] = "{$row['sigla']} - {$row['tipo_documento']}";
        }
        unset($query);
        $query = Puc::all()->toArray();
        $puc = array();
        foreach ($query as $row) {
            $puc[$row['id']] = "{$row['puc']} - {$row['descripcion']}";
        }
        unset($query);
        $query = TipoContribuyente::all()->toArray();
        $terceroComprobante = array();
        foreach ($query as $row) {
            $terceroComprobante[$row['id']] = $row['nombre'];
        }
        return $form
            ->columns(8)
            ->schema([
                //

                Section::make('Creación de Comprobante')
                    ->headerActions([
                        Action::make('Descargar plantilla')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->url(fn (): string => \asset('storage/plantilla.xlsx')),
                    ])
                    ->schema([
                        Toggle::make('usar_plantilla')
                            ->label('Usar plantilla')
                            ->live()
                            ->visibleOn('create')
                            ->columnSpan(2),
                        Select::make('plantilla')
                            ->label('Plantilla')
                            ->columnSpan(2)
                            ->options(function () {
                                $query = Comprobante::where('is_plantilla', '=', true)->get()->pluck('descripcion_comprobante', 'id');
                                return $query;
                            })
                            ->disabled(function (Get $get, Set $set): bool {
                                if ($get('usar_plantilla')) {
                                    $template = Comprobante::all()->find($get('plantilla'));
                                    if (!is_null($template)) {
                                        $template = $template->toArray();
                                        $set('tipo_documento_contables_id', $template['tipo_documento_contables_id']);
                                        $set('n_documento', $template['n_documento']);
                                        $set('tercero_id', $template['tercero_id']);
                                        $set('is_plantilla', 0);
                                        $set('descripcion_comprobante', $template['descripcion_comprobante']);
                                    }
                                    return false;
                                } else {
                                    return true;
                                }
                            })
                            ->visibleOn('create')
                            ->live(),
                        Toggle::make('is_plantilla')
                            ->label('¿Guardar Plantilla?')
                            ->required()
                            ->visibleOn('create')
                            ->columnSpan(2),
                        DatePicker::make('fecha_comprobante')
                            ->label('Fecha de comprobante')
                            ->required()
                            ->suffixIcon('heroicon-m-calendar-days')
                            ->columnSpan(2)
                            ->native(false)
                            ->disabled(function (Get $get, Set $set): bool {
                                $id = $get('tipo_documento_contables_id');
                                if (!is_null($id)) {
                                    $isDateModified = TipoDocumentoContable::find($id)->toArray()['fecha_modificable'];
                                    if ($isDateModified == 1) {
                                        return false;
                                    } else {
                                        $set('fecha_comprobante', date('Y-m-d'));
                                        return true;
                                    }
                                } else {
                                    return false;
                                }
                            }),

                        Select::make('tipo_documento_contables_id')
                            ->label('Tipo de Documento')
                            ->columnSpan(2)
                            ->options(TipoDocumentoContable::where('uso_contable', true)->pluck('tipo_documento', 'id'))
                            ->required()
                            ->native(false)
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    // Usa el modelo para obtener el último numerador del tipo de documento seleccionado
                                    $ultimoNumerador = TipoDocumentoContable::where('id', $state)
                                        ->value('numerador');

                                    // Si se encontró un numerador, incrementar en 1 y asignarlo al campo n_documento
                                    if ($ultimoNumerador !== null) {
                                        $set('n_documento', $ultimoNumerador + 1);
                                    }
                                }
                            })
                            ->live(),

                        TextInput::make('n_documento')
                            ->label('Nº de Documento')
                            ->columnSpan(2)
                            ->rule('regex:/^[0-9]+$/')
                            ->required(),
                        Select::make('tercero_id')
                            ->label('Tercero Comprobante')
                            ->required()
                            ->columnSpan(4)
                            ->native(false)
                            ->relationship('tercero', 'tercero_id')
                            ->searchable(),


                        Textarea::make('descripcion_comprobante')
                            ->label('Descripcion del Comprobante')
                            ->columnSpan(8)
                            ->autocomplete(false)
                            ->required(),

                        TextInput::make('total_debito')->label('Total Debitos')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->prefix('$')
                            ->disabled(function (Get $get, Set $set) {
                                $total = 0;
                                foreach ($get('detalle') as $detalle) {
                                    $total += floatval($detalle['debito']);
                                }
                                $set('total_debito', $total);
                                return true;
                            })
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ]),

                        TextInput::make('total_credito')->label('Total Creditos')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->prefix('$')
                            ->disabled(function (Get $get, Set $set) {
                                $total = 0;
                                foreach ($get('detalle') as $detalle) {
                                    $total += floatval($detalle['credito']);
                                }
                                $set('total_credito', $total);
                                return true;
                            })
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ]),

                    ]),

                TableRepeater::make('detalle')
                    ->label('Detalle comprobante')
                    ->relationship('comprobanteLinea', function ($query) {
                        $query->limit(30);
                    })
                    ->schema([
                        Select::make('pucs_id')
                            ->label('Cuenta PUC')
                            ->live()
                            ->options(Puc::where('movimiento', true)->pluck('puc', 'id'))
                            ->native(false)
                            ->searchable()
                            ->required(),
                        Select::make('tercero_id')
                            ->label('Tercero Registro')
                            ->required()
                            ->native(false)
                            ->relationship('tercero', 'tercero_id')
                            ->markAsRequired(false)
                            ->searchable(),
                        TextInput::make('descripcion_linea')
                            ->label('Descripcion Linea')
                            ->required(),
                        TextInput::make('debito')
                            ->placeholder('Debito')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->prefix('$')
                            ->live(onBlur: true),
                        TextInput::make('credito')
                            ->placeholder('Credito')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->prefix('$')
                            ->live(onBlur: true),
                    ])
                    ->reorderable()
                    ->cloneable()
                    ->grid(4)
                    ->collapsible()
                    ->defaultItems(1)
                    ->visible(function () {
                        if (Route::is('filament.admin.procesos-contabilidad.resources.comprobantes.view')) return false;
                        else return true;
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->label('Nº'),
                TextColumn::make('tipoDocumentoContable.tipo_documento')
                    ->label('Tipo de Documento Contable')
                    ->searchable(),
                TextColumn::make('n_documento')
                    ->label('Nº de documento')
                    ->searchable(),
            ])

            ->filters([
                //
                Filter::make('created_at')->form([
                    DatePicker::make('created_from')
                        ->label('Creado desde')
                        ->native(false),
                    DatePicker::make('created_until')
                        ->label('Creado hasta')
                        ->native(false)
                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('fecha_comprobante', ">=", $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('fecha_comprobante', "<=", $date),
                        );
                })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Ver'),
                Tables\Actions\EditAction::make()
                    ->label('Modificar'),
            ])
            ->bulkActions([])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('Sin comprobantes');
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
            'index' => Pages\ListComprobantes::route('/'),
            'create' => Pages\CreateComprobante::route('/create'),
            'edit' => Pages\EditComprobante::route('/{record}/edit'),
            'view' => Pages\ViewComprobante::route('/{record}'),
        ];
    }
}
