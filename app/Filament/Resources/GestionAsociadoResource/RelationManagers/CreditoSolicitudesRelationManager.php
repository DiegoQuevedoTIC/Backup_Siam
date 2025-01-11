<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use App\Models\Barrio;
use App\Models\CarteraEncabezado;
use App\Models\Ciudad;
use App\Models\CreditoLinea;
use App\Models\CreditoSolicitud;
use App\Models\Garantia;
use App\Models\Pagaduria;
use App\Models\CuotaEncabezado;
use App\Models\PlanDesembolso;
use App\Models\Profesion;
use App\Models\Tasa;
use App\Models\Tercero;
use App\Models\TipoIdentificacion;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Actions\Action as ActionsTable;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions;
use Filament\Forms\Set;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Get;
use Closure;
use Filament\Support\Enums\MaxWidth;

class CreditoSolicitudesRelationManager extends RelationManager
{
    protected static string $relationship = 'creditoSolicitudes';

    public bool $show = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('observaciones')
            ->columns([
                Tables\Columns\TextColumn::make('solicitud')->label('Nro solicitud')->default('N/A'),
                Tables\Columns\TextColumn::make('estado')->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'P' => 'gray',
                        'N' => 'danger',
                        'M' => 'gray',
                        'A' => 'success',
                        'C' => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'P' => 'PENDIENTE',
                        'N' => 'NEGADA',
                        'M' => 'MONTO DESEMBOLSADO',
                        'A' => 'APROBADA',
                        'C' => 'CANCELADA',
                    }),
                Tables\Columns\TextColumn::make('linea')->label('Linea de credito')->default('N/A'),
                Tables\Columns\TextColumn::make('tasa_id')->label('Interes Corriente')->formatStateUsing(fn($state) => $state !== null ? number_format($state, 2) . ' %' : 'N/A')->default('N/A'),
                Tables\Columns\TextColumn::make('nro_cuotas_max')->label('Nro Cuotas')->default('N/A'),
                Tables\Columns\TextColumn::make('fecha_solicitud')->label('Fecha Solicitud')->default('N/A'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ActionsTable::make('plan')->label('Plan de pago'),
                ActionsTable::make('actualizar_datos')->label('Actualización Datos')
                    ->color('info')
                    ->fillForm(fn(): array => [
                        $tercero = Tercero::find($this->getOwnerRecord()->id),
                        'nro_identificacion' => $tercero->tercero_id,
                        'nombres' => $tercero->nombres,
                        'primer_apellido' => $tercero->primer_apellido,
                        'segundo_apellido' => $tercero->segundo_apellido,
                        'tipo_documento' => $tercero->TipoIdentificacion->nombre,
                        'ocupacion' => $tercero->profesion_id,
                        'direccion' => $tercero->direccion,
                        'barrio' => $tercero->barrio_id,
                        'ciudad' => $tercero->ciudad_id,
                        'nro_celular_1' => $tercero->celular,
                        'nro_celular_2' => $tercero->nro_celular_2,
                        'nro_telefono_fijo' => $tercero->telefono,
                        'correo' => $tercero->email,
                        'total_activos' => $tercero->InformacionFinanciera->total_activos ?? 0,
                        'total_pasivos' => $tercero->InformacionFinanciera->total_pasivos ?? 0,
                        'salario' => $tercero->InformacionFinanciera->salario ?? 0,
                        'honorarios' => $tercero->InformacionFinanciera->honorarios ?? 0,
                        'gastos_financieros' => $tercero->InformacionFinanciera->gastos_financieros ?? 0,
                        'creditos_hipotecarios' => $tercero->InformacionFinanciera->creditos_hipotecarios ?? 0,
                        'otros_gastos' => $tercero->InformacionFinanciera->otros_gastos ?? 0,
                    ])
                    ->form([
                        Section::make('Actualización de Datos')
                            ->description('Tercero Natural')
                            ->icon('heroicon-m-user')
                            ->schema([
                                Forms\Components\TextInput::make('nro_identificacion')->label('Nro Identificación')->disabled(),
                                Forms\Components\TextInput::make('nombres')->label('Nombre')->required(),
                                Forms\Components\TextInput::make('primer_apellido')->label('Primer Apellido')->required(),
                                Forms\Components\TextInput::make('segundo_apellido')->label('Segundo Nombre')->required(),
                                Forms\Components\Select::make('tipo_documento')->label('Tipo de Documento')->required()
                                    ->options(TipoIdentificacion::all()->pluck('nombre', 'id'))
                                    ->searchable(),
                                Forms\Components\Select::make('ocupacion')->label('Ocupación')->required()
                                    ->options(Profesion::all()->pluck('nombre', 'id'))
                                    ->searchable(),
                                Forms\Components\TextInput::make('direccion')->label('Dirección')->required(),
                                Forms\Components\Select::make('barrio')->label('Barrio')->required()
                                    ->options(Barrio::all()->pluck('nombre', 'id'))
                                    ->searchable(),
                                Forms\Components\Select::make('ciudad')->label('Ciudad')->required()
                                    ->options(Ciudad::all()->pluck('nombre', 'id'))
                                    ->searchable(),
                                Forms\Components\TextInput::make('nro_celular_1')->label('Nro Celular 1')->required(),
                                Forms\Components\TextInput::make('nro_celular_2')->label('Nro Celular 2'),
                                Forms\Components\TextInput::make('nro_telefono_fijo')->label('Telefono Fijo')->required(),
                                Forms\Components\TextInput::make('correo')->label('Correo')->required(),
                            ])->columns(3),
                        Section::make('Datos Financieros')
                            ->description('Aqui debes actualizar los datos financieros, de lo contrario no se modifica nada')
                            ->icon('heroicon-m-wallet')
                            ->schema([
                                Forms\Components\TextInput::make('total_activos')->label('Total Activos')->mask('9999999,99'),
                                Forms\Components\TextInput::make('total_pasivos')->label('Total Pasivos')->mask('9999999,99'),
                                Forms\Components\TextInput::make('salario')->label('Salario')->mask('9999999.99'),
                                Forms\Components\TextInput::make('honorarios')->label('Honorarios')->mask('9999999,99'),
                                Forms\Components\TextInput::make('gastos_financieros')->label('Gastos Financieros')->mask('9999999,99'),
                                Forms\Components\TextInput::make('creditos_hipotecarios')->label('Credito Hipotecario')->mask('9999999,99'),
                                Forms\Components\TextInput::make('otros_gastos')->label('Otros Gastos')->mask('9999999,99'),
                            ])->columns(3),
                    ])->action(function (array $data): void {

                        $tercero = Tercero::find($this->getOwnerRecord()->id);

                        $tercero->update([
                            'nombres' => $data['nombres'],
                            'primer_apellido' => $data['primer_apellido'],
                            'segundo_apellido' => $data['segundo_apellido'],
                            'direccion' => $data['direccion'],
                            'telefono' => $data['nro_telefono_fijo'],
                            'celular' => $data['nro_celular_1'],
                            'email' => $data['correo'],
                            'ciudad_id' => $data['ciudad'],
                            'barrio_id' => $data['barrio'],
                        ]);

                        // Disparamos evento para descargar sollicitud por PDF
                        $this->dispatch('showCreateSolicitud');

                        Notification::make()
                            ->title('Se actualizaron los datos correctamente')
                            ->icon('heroicon-m-check-circle')
                            ->body('Los datos fueron actualizados correctamente')
                            ->success()
                            ->send();
                    })->slideOver()
                    ->modalSubmitActionLabel('Actualizar'),
                ActionsTable::make('create_solicitud_credito')->label('Crear Solicitud de credito')
                    ->form([
                        Section::make()
                            ->schema([
                                Forms\Components\Select::make('linea')
                                    ->label('Linea de credito')
                                    ->searchable()
                                    ->options(function () {
                                        return CreditoLinea::all()->pluck('descripcion', 'id');
                                    })
                                    ->live()
                                    ->required(function (Get $get, Set $set) {
                                        $creditoLinea = CreditoLinea::find($get('linea'));
                                        if (!is_null($creditoLinea)) {
                                            $set('vlr_solicitud', $creditoLinea->monto_max);
                                            $set('nro_cuotas_max', $creditoLinea->nro_cuotas_max);
                                            $set('nro_cuotas_gracia', $creditoLinea->nro_cuotas_gracia);
                                        }
                                    }),
                                Forms\Components\Select::make('empresa')
                                    ->label('Pagaduria')
                                    ->required()
                                    ->searchable()
                                    ->options(fn() => Pagaduria::query()->pluck('nombre', 'id')),
                                Forms\Components\Select::make('tipo_desembolso')
                                    ->options([
                                        'V' => 'Descuento ventanilla',
                                        'N' => 'Descuento nomina'
                                    ])
                                    ->label('Tipo de desembolso')
                                    ->native(false),
                                Forms\Components\TextInput::make('vlr_solicitud')
                                    ->label('Valor Solicitud')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(fn(Get $get): int => CreditoLinea::find($get('linea'))->monto_max ?? 0)
                                    ->required()
                                    ->rules([
                                        fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            $lineaCredito = CreditoLinea::find($get('linea'));
                                            $montoMax = floatval($lineaCredito->monto_max);
                                            //dd($montoMax, $attribute, $value);

                                            if ($lineaCredito) {
                                                if (floatval($value) > $montoMax) {
                                                    $fail('El monto de la solicitud no puede superar el monto máximo permitido.');
                                                }
                                            }
                                        },
                                    ]),
                                Forms\Components\TextInput::make('nro_cuotas_max')
                                    ->label('Plazo')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->maxValue(fn(Get $get): int => CreditoLinea::find($get('linea'))->nro_cuotas_max ?? 0)
                                    ->helperText('Plazo maximo de pago'),
                                Forms\Components\TextInput::make('nro_cuotas_gracia')
                                    ->minValue(0)
                                    ->maxValue(fn(Get $get): int => CreditoLinea::find($get('linea'))->nro_cuotas_gracia ?? 0)
                                    ->label('Cuota Gracia')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\DatePicker::make('fecha_primer_vto')
                                    ->label('Fecha cuota 1')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->minDate(now())
                                    ->helperText('Fecha de la primera cuota'),
                                Forms\Components\Select::make('tasa_id')
                                    ->label('Tasa interes')
                                    ->required()
                                    ->options(fn() => Tasa::query()->pluck('nombre', 'id'))
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nombre')
                                            ->label('Nombre de la tasa')
                                            ->required()
                                            ->columns(2),
                                        Forms\Components\TextInput::make('tasa')
                                            ->label('Valor de la tasa')
                                            ->numeric()
                                            ->required()
                                            ->columns(2),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        return Tasa::create($data)->id;
                                    })
                                    ->native(false),
                                Forms\Components\Select::make('tercero_asesor')
                                    ->label('Codigo Asesor')
                                    ->searchable()
                                    ->options(fn() => Tercero::query()
                                        ->select(DB::raw("id, CONCAT(nombres, ' ', primer_apellido) AS nombre_completo"))
                                        ->pluck('nombre_completo', 'id'))
                                    ->required(),
                                Forms\Components\Textarea::make('observaciones')
                                    ->label('Observaciones')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Actions::make([
                                    Action::make('garantia')
                                        ->label('Garantias')
                                        ->color('info')
                                        ->form([
                                            Section::make('Creación de Garantia')
                                                ->description('Formulario para crear garantia')
                                                ->icon('heroicon-m-shield-check')
                                                ->schema([
                                                    Forms\Components\Select::make('tipo_garantia_id')->label('Tipo de garantia')
                                                        ->options(['P' => 'Garantias personales', 'R' => 'Garantias reales'])
                                                        ->searchable()
                                                        ->required(),
                                                    Forms\Components\TextInput::make('nro_escr_o_matri')->label('Nro escritura / Matricula'),
                                                    Forms\Components\Select::make('tercero_garantia')->label('Tercero Garantia')
                                                        ->options(fn() => Tercero::query()
                                                            ->select(DB::raw("id, CONCAT(nombres, ' ', primer_apellido, ' ', tercero_id) AS nombre_completo"))
                                                            ->pluck('nombre_completo', 'id'))
                                                        ->searchable()
                                                        ->required(),
                                                    Forms\Components\TextInput::make('ciudad_registro')->label('Ciudad Registro'),
                                                    Forms\Components\TextInput::make('valor_avaluo')->label('Valor Avaluo')->numeric()->default(0),
                                                    Forms\Components\DatePicker::make('fecha_avaluo')->label('Fecha avaluo'),
                                                    Forms\Components\Checkbox::make('bien_con_prenda')->label('Bien con prenda'),
                                                    Forms\Components\Checkbox::make('bien_sin_prenda')->label('Bien sin prenda'),
                                                    Forms\Components\TextInput::make('valor_avaluo_comercial')->label('Valor Avaluo Comercial')->numeric()->default(0),
                                                    Forms\Components\Textarea::make('observaciones')->label('Observaciones')->columnSpanFull(),
                                                ])->columns(3),
                                        ])->action(function (array $data): void {

                                            Garantia::create([
                                                'asociado_id' => $this->getOwnerRecord()->id,
                                                'tipo_garantia_id' => $data['tipo_garantia_id'],
                                                'nro_escr_o_matri' => $data['nro_escr_o_matri'],
                                                'direccion' => $data['direccion'],
                                                'ciudad_registro' => $data['ciudad_registro'],
                                                'valor_avaluo' => $data['valor_avaluo'],
                                                'fecha_avaluo' => $data['fecha_avaluo'],
                                                'bien_con_prenda' => $data['bien_con_prenda'],
                                                'bien_sin_prenda' => $data['bien_sin_prenda'],
                                                'valor_avaluo_comercial' => $data['valor_avaluo_comercial'],
                                                'observaciones' => $data['observaciones'],
                                            ]);

                                            Notification::make()
                                                ->title('Se crearon los datos correctamente')
                                                ->icon('heroicon-m-check-circle')
                                                ->body('Los datos fueron creados correctamente')
                                                ->success()
                                                ->send();
                                        })
                                        ->modalSubmitActionLabel('Crear Garantia'),
                                    Action::make('analisis_cupo')
                                        ->label('Analisis cupo')
                                        ->color('info')
                                        ->action(function () {
                                            //
                                        })->disabled(),
                                    Action::make('capacidad_endeudamiento')
                                        ->label('Capacidad de endeudamiento')
                                        ->color('info')
                                        ->action(function () {
                                            //
                                        })->disabled(),
                                ])->columnSpanFull(),
                            ])->columns(2)
                    ])
                    ->action(function (array $data): void {

                        // validamos si debe tener garantia
                        $garantia = CreditoLinea::find($data['linea']);

                        if ($garantia->cant_gar_real >= 1 && $garantia->cant_gar_pers >= 1) {
                            if (count($this->getOwnerRecord()->garantias) < $garantia->cant_gar_real && count($this->getOwnerRecord()->garantias) < $garantia->cant_gar_pers) {


                                $this->dispatch('noCloseModal');

                                Notification::make()
                                    ->title('Atención')
                                    ->icon('heroicon-m-exclamation-circle')
                                    ->body('Se solicita crear al menos una garantia para proceder con la creación de la solicitud de credito')
                                    ->warning()
                                    ->duration(5000)
                                    ->send();


                                return;
                            }
                        }

                        // inicialización de transacion para garantizar integridad de datos
                        DB::transaction(function () use ($data) {


                            $tasa = Tasa::find($data['tasa_id']);
                            $data['tasa_float'] = floatval($tasa->tasa);
                            $linea = CreditoLinea::find($data['linea'])->first();

                            // Creamos la solicitud
                            $credito = CreditoSolicitud::create([
                                'asociado_id' => $this->getOwnerRecord()->id,
                                'linea' => $data['linea'],
                                'empresa' => $data['empresa'],
                                'asociado_id' => $this->getOwnerRecord()->id,
                                'asociado' => $this->getOwnerRecord()->codigo_interno_pag,
                                'tipo_desembolso' => $data['tipo_desembolso'],
                                'periodo_pago' => 1, // Periodo de pago mensual
                                'moneda' => 1, // Moneda pesos
                                'vlr_solicitud' => $data['vlr_solicitud'],
                                'nro_cuotas_max' => $data['nro_cuotas_max'],
                                'nro_cuotas_gracia' => $data['nro_cuotas_gracia'],
                                'fecha_primer_vto' => $data['fecha_primer_vto'],
                                'tasa_id' => $data['tasa_id'],
                                'tercero_asesor' => $data['tercero_asesor'],
                                'observaciones' => $data['observaciones'],
                                'estado' => 'P',
                                'fecha_solicitud' => now()->format('Y-m-d'),
                                'usuario_crea' => auth()->user()->name
                            ]);

                            // Creamos el registro en la tabla cartera
                            $cartera_encabezado = CarteraEncabezado::create([
                                'cliente' => $this->getOwnerRecord()->codigo_interno_pag,
                                'linea' => $data['linea'],
                                'estado' => 'A',
                                'periodo_pago' => 1,
                                'moneda' => 1,
                                'interes_cte' => $data['tasa_float'],
                                'tipo_cuota' => $linea->tipo_cuota,
                                'forma_pago_int' => 'V',
                                'forma_descuento' => $this->getOwnerRecord()->tipo_vinculo_id,
                                'tipo_tasa' => $linea->tipo_tasa,
                                'nro_cuotas_gracia' => $data['nro_cuotas_gracia'],
                                'abonos_extra' => $linea->abonos_extra,
                                'vlr_abono' => 0.00,
                                'fecha_docto' => now()->format('Y-m-d'),
                                'fecha_primer_vto' => $data['fecha_primer_vto'],
                                'vlr_docto_vto' => $data['vlr_solicitud'],
                                'vlr_ini_cuota' => 0.00,
                                'fecha_desembolso' => null,
                                'vlr_desembolsado' => 0.00,
                                'nro_cuotas' => null,
                                'fecha_pago_total' => null,
                                'usuario_crea' => auth()->user()->name,
                                'empresa' => $data['empresa'],
                                'tercero_asesor' =>  $data['tercero_asesor']
                            ]);

                            $plan_desembolso = PlanDesembolso::create([
                                'solicitud_id' => $credito->id,
                                'plan_numero' => 1,
                                'fecha_plan' => now()->format('Y-m-d'),
                                'fecha_inicio' => $credito->fecha_primer_vto,
                                'valor_plan' => $credito->vlr_solicitud,
                                'modo_desembolso' => null,
                                'tipo_documento_enc' => 'PLI',
                                'nro_documento_vto_enc' => $cartera_encabezado->nro_docto,
                            ]);

                            // Creamos la composicion de la solicitud de credito
                            $credito_lineas_conceptos = DB::table('credito_lineas_conceptos')->where('linea_id', $credito->linea)->get()->toArray();
                            foreach ($credito_lineas_conceptos as $cartera_composicion_conceptos) {
                                DB::table('cartera_composicion_conceptos')->insert([
                                    'tipo_documento' => 'PLI',
                                    'numero_documento' => $cartera_encabezado->nro_docto,
                                    'concepto_descuento' => $cartera_composicion_conceptos->codigo_descuento,
                                    'prioridad' => $cartera_composicion_conceptos->prioridad,
                                    'valor' => $cartera_composicion_conceptos->valor,
                                    'valor_con_descuento' => $cartera_composicion_conceptos->valor_descuento,
                                    'porcentaje_descuento' => $cartera_composicion_conceptos->porcentaje_descuento,
                                    'comodin' => $cartera_composicion_conceptos->comodin
                                ]);
                            }

                            // Creamos toda la informacion necesaria para calcular la cuotas de pago
                            $cuotas = calcular_amortizacion($data['vlr_solicitud'], $data['tasa_float'], $data['nro_cuotas_max']);

                            // Inicializamos la variable para la primer cuota (valor)
                            $vlr_ini_cuota = 0.00;
                            $nro_cuotas = 0;

                            // Almacenamos todas las cuotas para luego usarlas en cutoas detalles
                            $cuotas_encabezados = array();

                            // Creamos todas las cuotas de la solicitud detallada
                            foreach ($cuotas as $cuota) {
                                // Crea un nuevo encabezado de cuota y agrega la amortización capital
                                $nuevo_encabezado = CuotaEncabezado::create([
                                    'tdocto' => 'PLI',
                                    'nro_docto' => $cartera_encabezado->nro_docto,
                                    'nro_cuota' => $cuota['periodo'],
                                    'consecutivo' => 1,
                                    'estado' => 'A',
                                    'iden_cuota' => 'N',
                                    'interes_cte' => $cuota['interes'],
                                    'interes_mora' => 0.00,
                                    'fecha_vencimiento' => null, // por definir calcular
                                    'fecha_pago_total' => null,
                                    'dias_mora' => 0,
                                    'vlr_cuota' => $cuota['pago'],
                                    'saldo_capital' => $cuota['saldo'],
                                    'vlr_abono_rec' => 0.00,
                                    'vlr_abono_ncr' => 0.00,
                                    'vlr_abono_dpa' => 0.00,
                                    'vlr_descuento' => 0.00,
                                    'forma_descuento' => $cartera_encabezado->forma_descuento,
                                    'vlr_cuentas_orden' => 0.00,
                                    'vlr_causado' => 0.00
                                ]);

                                $nuevo_encabezado['amortizacion_capital'] = $cuota['amortizacion_capital'];

                                // Agregar el nuevo encabezado al array de cuotas
                                array_push($cuotas_encabezados, $nuevo_encabezado);

                                // Actualizar variables adicionales si es necesario
                                $vlr_ini_cuota = $cuota['pago'];
                                $nro_cuotas = $cuota['periodo'] += $cuota['periodo'];
                                $suma = $cuota['pago'] += $cuota['pago'];
                            }

                            // Creamos los detalles de cada cuota
                            $conceptos_creditos = DB::table('cartera_composicion_conceptos')->where('numero_documento', $cartera_encabezado->nro_docto)->get()->toArray();

                            foreach ($cuotas_encabezados as $cuota) {
                                foreach ($conceptos_creditos as $concepto) {
                                    DB::table('cuotas_detalles')->insert([
                                        'tdocto' => 'PLI',
                                        'nro_docto' => $cartera_encabezado->nro_docto,
                                        'nro_cuota' => $cuota->nro_cuota,
                                        'consecutivo' => 1,
                                        'estado' => 'A',
                                        'vlr_detalle' => $concepto->concepto_descuento = (1) ? $cuota->amortizacion_capital : ((2) ? $cuota->interes_cte : 0.00),
                                        'con_descuento' => 1
                                    ]);
                                }
                            }

                            // Actualizamos el registro con el valor de la primera cuota
                            $cartera_encabezado->update([
                                'vlr_ini_cuota' => $vlr_ini_cuota,
                                'nro_cuotas' => $nro_cuotas
                            ]);

                            // Actualizamos la solicitud de credito con la suma total de cuotas
                            $credito->update([
                                'vlr_planes' => $suma
                            ]);

                            // Disparamos evento para descargar sollicitud por PDF
                            $this->dispatch('download', [[$this->getOwnerRecord(), $credito]]);

                            // Notificación visual para el usuario
                            Notification::make()
                                ->title('Se crearon los datos correctamente')
                                ->icon('heroicon-m-check-circle')
                                ->body('Los datos fueron creados correctamente')
                                ->success()
                                ->send();
                        }, 5);
                    })->extraAttributes([
                        'x-init' => "
                        window.addEventListener(`DOMContentLoaded`, function(event) {
                            show = false;
                        });
                        window.addEventListener(`showCreateSolicitud`, function(){
                            show = true;
                        });
                        window.addEventListener(`noCloseModal`, function(){
                            show = true;
                        });
                        ",
                        'x-data' => "{ show: false }",
                        'x-show' => "show",
                    ])
                /* ->modalContent(view('custom.modal.solicitud_credito')) */,
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver()->label('ver'),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading()
            ->defaultSort('id', 'desc');
    }
}


// Funcion para calcular las cuotas que se deben cancelar
function calcular_amortizacion($principal, $tasa_anual, $plazo_meses)
{
    $tasa_mensual = $tasa_anual / 12 / 100;
    $pago_mensual = $principal * $tasa_mensual * pow((1 + $tasa_mensual), $plazo_meses) / (pow((1 + $tasa_mensual), $plazo_meses) - 1);

    $saldo = $principal;
    $tabla_amortizacion = array();

    for ($periodo = 1; $periodo <= $plazo_meses; $periodo++) {
        $interes = $saldo * $tasa_mensual;
        $amortizacion_capital = $pago_mensual - $interes;
        $saldo -= $amortizacion_capital;

        array_push($tabla_amortizacion, array(
            'periodo' => $periodo,
            'pago' => round($pago_mensual, 2),
            'interes' => round($interes, 2),
            'amortizacion_capital' => round($amortizacion_capital, 2),
            'saldo' => round($saldo, 2)
        ));
    }

    return $tabla_amortizacion;
}


function showModal(): bool
{
    return false;
    sleep(5);
    return true;
}
