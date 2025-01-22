<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use App\Models\CuotaDescuento;
use App\Models\HistoricoDescuento;
use App\Models\Obligacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action as ActionsTable;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class ObligacionesRelationManager extends RelationManager
{
    protected static string $relationship = 'obligaciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('concepto')
            ->columns([
                Tables\Columns\TextColumn::make('fecha_vencimiento')->default('N/A'),
                Tables\Columns\TextColumn::make('con_descuento')->default('N/A'),
                Tables\Columns\TextColumn::make('descripcion_concepto')->default('N/A'),
                Tables\Columns\TextColumn::make('consecutivo')->default('N/A'),
                Tables\Columns\TextColumn::make('nro_cuota')->default('N/A'),
                Tables\Columns\TextColumn::make('vlr_cuota')->money('COP')->default('N/A'),
            ])
            ->filters([
                //
                Filter::make('vigente'),
                Filter::make('vencida'),
            ])
            ->headerActions([
                ActionsTable::make('programar_obligacion')->label('Programar Obligación')
                    ->form([
                        Section::make('Concepto Obligación')
                            ->description('Definir texto de ejemplo')
                            ->schema([
                                // ...
                                Forms\Components\Select::make('con_descuento')
                                    ->options(fn() => DB::table('concepto_descuentos')->pluck('descripcion', 'codigo_descuento'))
                                    ->searchable()
                                    ->required()
                                    ->label('Concepto Descuento'),
                                Forms\Components\TextInput::make('valor_descuento')
                                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                                    ->inputMode('decimal')
                                    ->prefix('$')
                                    ->autocomplete(false)
                                    ->required()
                                    ->numeric(),
                                Forms\Components\Checkbox::make('indefinido')->default(false)->live(),
                                Forms\Components\TextInput::make('plazo')->label('Plazo')->numeric()->autocomplete(false)
                                    ->visible(function (Get $get) {
                                        $indefinido = $get('indefinido');

                                        if ($indefinido) {
                                            return false;
                                        }

                                        return true;
                                    })->live(),
                                Forms\Components\DatePicker::make('fecha_inicio_descuento'),
                                Forms\Components\Select::make('periodo_descuento')
                                    ->options([
                                        1 => 'Mensual',
                                        2 => 'Quincenal'
                                    ])
                                    ->native(false),
                                Forms\Components\Select::make('estado')
                                    ->options([
                                        'A' => 'ACTIVO',
                                        'C' => 'CANCELADO',
                                        'I' => 'INACTIVO'
                                    ])
                                    ->native(false)
                            ])->columns(3)
                    ])->action(function (array $data) {
                        // inicialización de transacion para garantizar integridad de datos
                        DB::transaction(function () use ($data) {

                            //dd($data);

                            $con_relacion = DB::table('distribucion_descuentos')
                                ->where('con_descuento', $data['con_descuento'])
                                ->get()
                                ->toArray();

                            // Obtener el último registro para el cliente
                            $ultimoRegistro = DB::table('encabezado_descuentos')
                                ->where('cliente', $this->getOwnerRecord()->codigo_interno_pag)
                                ->latest()
                                ->first();

                            // Calcular el consecutivo
                            $consecutivo = $ultimoRegistro ? $ultimoRegistro->consecutivo + 1 : 1;

                            // Creamos los datos
                            $recordId = DB::table('encabezado_descuentos')->insertGetId([
                                'cliente' => $this->getOwnerRecord()->codigo_interno_pag,
                                'con_descuento' => $data['con_descuento'],
                                'consecutivo' => $consecutivo,
                                'periodo_desc' => $data['periodo_descuento'],
                                'fecha_generacion' => now()->format('Y-m-d'),
                                'fecha_inicio' => $data['fecha_inicio_descuento'],
                                'estado' => $data['estado'],
                                'tipo_descuento' => 1,
                                'vlr_descuento' => $data['valor_descuento'],
                                'indefinido' => $data['indefinido'] ? 'S' : 'N',
                                'porc_descuento' => 0,
                                'nro_cuotas' => $data['plazo'] ?? 0,
                                'distribuye' => $con_relacion ? 'S' : 'N',
                                'nivel' => 0,
                                'usuario_crea' => auth()->user()->name,
                                'es_hijo' => 'N'
                            ]);

                            // Obtener el objeto creado
                            $record = DB::table('encabezado_descuentos')->where('id', $recordId)->first();

                            // se realiza la creacion de la relacion con la distribucion
                            if ($con_relacion) {
                                foreach($con_relacion as $relacion) {
                                    $nuevovalor = $record->vlr_descuento * $relacion->porcentaje;
                                    $insert = DB::table('encabezado_descuentos')->insertGetId([
                                        'cliente' => $record->cliente,
                                        'con_descuento' => $relacion->con_descuento,
                                        'consecutivo' => $record->consecutivo + 1,
                                        'periodo_desc' => $record->periodo_desc,
                                        'fecha_generacion' => $record->fecha_generacion,
                                        'fecha_inicio' => $record->fecha_inicio,
                                        'estado' => $record->estado,
                                        'tipo_descuento' => 1,
                                        'vlr_descuento' => $nuevovalor,
                                        'indefinido' => $record->indefinido,
                                        'porc_descuento' => $record->porc_descuento,
                                        'nro_cuotas' => $record->nro_cuotas,
                                        'distribuye' => 'N',
                                        'nivel' => $record->nivel,
                                        'usuario_crea' => $record->usuario_crea,
                                        'es_hijo' => 'S',
                                        'con_descuento_padre' => $record->con_descuento,
                                        'consecutivo_padre' => $record->consecutivo
                                    ]);

                                    $record_2 = DB::table('encabezado_descuentos')->where('id', $insert)->first();

                                    // Obtener el último registro para el cliente
                                    $ultimoRegistro2 = DB::table('detalle_vencimiento_descuento')
                                        ->where('cliente', $this->getOwnerRecord()->codigo_interno_pag)
                                        ->latest()
                                        ->first();

                                    // Calcular el consecutivo
                                    $consecutivo2 = $ultimoRegistro2 ? $ultimoRegistro2->consecutivo + 1 : 1;

                                    $nro_cuota = $ultimoRegistro2 && !is_null($ultimoRegistro2->nro_cuota)
                                        ? $ultimoRegistro2->nro_cuota + 1
                                        : 1;

                                    // se inserta detalle de vencimiento
                                    DB::table('detalle_vencimiento_descuento')->insert([
                                        'cliente' => $record->cliente,
                                        'con_descuento' => $relacion->con_relacion,
                                        'consecutivo' => $consecutivo2,
                                        'nro_cuota' => $nro_cuota,
                                        'fecha_vencimiento' => $record->fecha_inicio,
                                        'estado' => 'A',
                                        'vlr_cuota' => $record_2->vlr_descuento,
                                        'abono_cuota' => 0,
                                        'consecutivo_padre' => $record_2->consecutivo_padre
                                    ]);
                                }
                            }


                            Notification::make()
                                ->title('Se crearon los datos correctamente')
                                ->icon('heroicon-m-check-circle')
                                ->body('Los datos fueron creados correctamente')
                                ->success()
                                ->send();
                        }, 5);
                    })
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
