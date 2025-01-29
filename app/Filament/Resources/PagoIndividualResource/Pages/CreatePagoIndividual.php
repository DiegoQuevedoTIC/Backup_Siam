<?php

namespace App\Filament\Resources\PagoIndividualResource\Pages;

use App\Filament\Resources\PagoIndividualResource;
use App\Models\CarteraEncabezado;
use App\Models\Comprobante;
use App\Models\ComprobanteLinea;
use App\Models\CreditoLinea;
use App\Models\Puc;
use App\Models\Tercero;
use App\Models\TipoDocumentoContable;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class CreatePagoIndividual extends CreateRecord
{
    protected static string $resource = PagoIndividualResource::class;
    protected static string $view = 'custom.tesoreria.create-pagos-individual';

    public bool $show = false;
    public $cliente;
    public $concepto_descuento;
    public $efectivo;
    public $cheque;
    public $valor_abonar;
    public $aplica_valor_a_total;
    public $tipo_documento_id;
    public $cuentaCapital;
    public $tipo_pago;
    public $pendiente;
    public $nro_docto_actual;


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo_documento')
                    ->label('Tipo de documento')
                    ->options(TipoDocumentoContable::query()
                        ->where('uso_pago', true)
                        ->select(DB::raw("id, CONCAT(sigla, ' - ', tipo_documento) AS nombre_completo"))
                        ->pluck('nombre_completo', 'id'))
                    ->live()
                    ->columnSpan(1)
                    ->searchable()
                    ->required(function (Get $get, Set $set) {
                        $this->tipo_documento_id = $get('tipo_documento');
                        if ($this->tipo_documento_id) {
                            $numerador = TipoDocumentoContable::where('id', $this->tipo_documento_id)->first()->numerador;
                            $set('nro_documento', $numerador);
                        }
                        return false;
                    }),
                TextInput::make('nro_documento')
                    ->label('Nro de documento')
                    ->disabled(),
                Select::make('tipo_pago')
                    ->label('Tipo de pago')
                    ->options(DB::table('concepto_descuentos')
                        ->where('identificador_concepto', 'PG')
                        ->pluck('descripcion', 'cuenta_contable'))
                    ->live()
                    ->columnSpan(1)
                    ->searchable()
                    ->required(function (Get $get) {
                        $this->tipo_pago = $get('tipo_pago');
                        return true;
                    }),
                TextInput::make('fecha')
                    ->prefixIcon('heroicon-c-calendar-days')
                    ->disabled()->default(now()->format('Y-m-d')),
                TextInput::make('cliente')
                    ->live(onBlur: true)
                    ->placeholder('Nro identificación cliente')
                    ->prefixIcon('heroicon-c-magnifying-glass-circle')
                    ->required(function (Get $get, Set $set) {
                        $valor = $get('cliente');
                        if ($valor) {
                            $asociado = Tercero::where('tercero_id', $valor)->first();

                            if ($asociado) {

                                $set('nombre', $asociado->nombres . ' ' . $asociado->primer_apellido . ' ' . $asociado->segundo_apellido);
                                $set('direccion', $asociado->direccion);
                                $set('telefono', $asociado->celular);

                                $this->cliente = $asociado;
                                $this->show = true;
                            }
                        }
                        return false;
                    }),
                TextInput::make('nombre')
                    ->placeholder('Nombre del cliente')
                    ->prefixIcon('heroicon-c-user'),
                TextInput::make('direccion')
                    ->placeholder('Dirección del cliente')
                    ->prefixIcon('heroicon-c-map'),
                TextInput::make('telefono')
                    ->placeholder('Telefono del cliente'),

                Section::make('Información de pago')
                    ->schema([
                        TextInput::make('efectivo')
                            ->placeholder('Monto efectivo')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$')
                            ->live(onBlur: true)
                            ->default(function (Get $get) {
                                $this->efectivo = $get('efectivo');
                                return 0;
                            }),
                        TextInput::make('cheque')
                            ->placeholder('Nro cheque')
                            ->prefixIcon('heroicon-c-credit-card')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$')
                            ->live(onBlur: true)
                            ->disabled(function (Get $get) {
                                $this->cheque = $get('cheque');
                                return false;
                            }),
                        TextInput::make('valor_abonar')
                            ->placeholder('Valor a abonar')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$')
                            ->live(onBlur: true)
                            ->disabled(function (Get $get) {
                                $this->valor_abonar = $get('valor_abonar');
                                return false;
                            }),
                        TextInput::make('valor_total')
                            ->placeholder('Valor total')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$')
                            ->readOnly()
                            ->disabled(function (Get $get, Set $set) {
                                // Obtener los valores y convertirlos a números
                                $e = (float)$get('efectivo'); // Convertir a float
                                $c = (float)$get('cheque');   // Convertir a float
                                $va = (float)$get('valor_abonar'); // Convertir a float

                                // Realizar la suma
                                $vt = $e + $c + $va;

                                // Establecer el valor total
                                $set('valor_total', $vt);

                                return false;
                            }),
                        TextInput::make('pendiente_por_aplicar')
                            ->placeholder('Pendiente por aplicar')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$')
                            ->readOnly()
                            ->disabled(function (Get $get, Set $set): bool {
                                // Convertir los valores a float antes de restar
                                $valorTotal = (float)$get('valor_total');
                                $totalAPagar = (float)$get('total_a_pagar');

                                // Realizar la resta
                                $pendientePorAplicar = $valorTotal - $totalAPagar;

                                if ($pendientePorAplicar < 0) {
                                    $this->pendiente = $pendientePorAplicar;
                                } else {
                                    $this->pendiente = $pendientePorAplicar;
                                }

                                // Establecer el valor de "pendiente_por_aplicar"
                                $set('pendiente_por_aplicar', $pendientePorAplicar);

                                return false;
                            }),
                        TextInput::make('total_a_pagar')
                            ->placeholder('Total a pagar')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$')
                            ->live()
                            ->disabled(function (Set $set): bool {

                                if ($this->cliente) {
                                    $sumatoria = DB::table('cartera_encabezados')
                                        ->where('cliente', $this->cliente->tercero_id)
                                        ->where('estado', 'A')
                                        ->where('tdocto', 'PAG')
                                        ->sum('vlr_congelada');


                                    $sumatoriaLiquidacion = DB::table('cartera_encabezados')
                                        ->where('cliente', $this->cliente->tercero_id)
                                        ->where('estado', 'A')
                                        ->where('tdocto', 'PAG')
                                        ->sum('vlr_cuentas_orden');

                                    $sumatoriaObligaciones = DB::table('detalle_vencimiento_descuento')
                                        ->where('cliente', $this->cliente->tercero_id)
                                        ->where('estado', 'A')
                                        ->sum('vlr_congelada');

                                    $otros_conceptos = DB::table('tmp_vencimiento_descuento')
                                        ->where('cliente', $this->cliente->tercero_id)
                                        ->sum('valor');

                                    $total_a_pagar = $sumatoria + $otros_conceptos + $sumatoriaLiquidacion + $sumatoriaObligaciones + $this->aplica_valor_a_total;

                                    $set('total_a_pagar', $total_a_pagar);
                                }

                                return false;
                            }),
                    ])->columns(3)
            ])
            ->columns(4);
    }

    public function nroDoctoActual($nro_docto)
    {
        $this->nro_docto_actual = $nro_docto;
    }

    public function updateValorAplicado($nuevo_valor, $id)
    {
        DB::table('detalle_vencimiento_descuento')->where('id', $id)->update([
            'vlr_congelada' => $nuevo_valor
        ]);

        (float)$this->aplica_valor_a_total += $nuevo_valor;
    }

    public function calcularIntereses(int $nro_docto)
    {
        //dd($nro_docto);
        if (!$nro_docto) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('No se puede calcular interés del crédito seleccionado')
                ->warning()
                ->duration(5000)
                ->send();
        }

        // Obtenemos las cuotas
        $cuotasEncabezado = DB::table('cuotas_encabezados')
            ->where('nro_docto', $nro_docto)
            ->where('tdocto', 'PAG')
            ->where('estado', 'C')
            ->orderBy('fecha_vencimiento', 'desc')
            ->get();

        $interes = 0;

        // Validamos que haya al menos una cuota
        if ($cuotasEncabezado->isEmpty()) {
            $carteraEncabezado = DB::table('cartera_encabezados')
                ->where('nro_docto', $nro_docto)
                ->first();

            // Tomamos la fecha de vencimiento de la primera cuota
            $primeraFechaVencimiento = Carbon::parse($carteraEncabezado->fecha_desembolso);


            // Calculamos los días de mora con base en la primera fecha
            $diasMora = $primeraFechaVencimiento->diffInDays(Carbon::parse(now()));

            // Calculamos el interés para esta cuota
            $interes += $carteraEncabezado->vlr_saldo_actual * ($diasMora * $carteraEncabezado->interes_mora);
        } else {
            // Tomamos la fecha de vencimiento de la primera cuota
            $primeraFechaVencimiento = Carbon::parse($cuotasEncabezado[0]->fecha_vencimiento);

            foreach ($cuotasEncabezado as $cuota) {
                // Calculamos los días de mora con base en la primera fecha
                $diasMora = $primeraFechaVencimiento->diffInDays(Carbon::parse(now()));

                // Calculamos el interés para esta cuota
                $interes += $cuota->vlr_cuota * ($diasMora * $cuota->interes_mora);
            }
        }



        // Retornamos los datos de interés calculado
        return [
            'primera_fecha' => $primeraFechaVencimiento,
            'segunda_fecha' => now(),
            'interes_mora' => number_format($interes, 2)
        ];
    }

    public function aplicarValor(int $nro_docto, float $valorAplicado)
    {

        DB::table('cartera_encabezados')->where('nro_docto', $nro_docto)->update([
            'vlr_congelada' => floatval($valorAplicado)
        ]);

        Notification::make()
            ->title('Atención')
            ->icon('heroicon-m-check-circle')
            ->body('Valor aplicado correctamente')
            ->success()
            ->duration(5000)
            ->send();
    }

    public function aplicarValorLiquidacion(int $nro_docto, float $valorAplicado)
    {
        //dd($nro_docto, $valorAplicado);

        DB::table('cartera_encabezados')->where('nro_docto', $nro_docto)->update([
            'vlr_cuentas_orden' => floatval($valorAplicado)
        ]);

        Notification::make()
            ->title('Atención')
            ->icon('heroicon-m-check-circle')
            ->body('Valor aplicado correctamente')
            ->success()
            ->duration(5000)
            ->send();
    }

    public function generarLiquidacion(int $nro_docto)
    {
        //dd($nro_docto);
        return  DB::table('cuotas_detalles as cds')
            ->join('concepto_descuentos as cd', 'cds.con_descuento', '=', 'cd.codigo_descuento')
            ->join('cartera_composicion_conceptos as ccc', function ($join) {
                $join->on('ccc.tipo_documento', '=', 'cds.tdocto')
                    ->on('ccc.numero_documento', '=', 'cds.nro_docto')
                    ->on('cds.con_descuento', '=', 'ccc.concepto_descuento');
            })
            ->where('cds.tdocto', 'PAG')
            ->where('cds.nro_docto', $nro_docto)
            ->where('cds.estado', 'A')
            ->orderBy('cds.nro_cuota')
            ->orderBy('ccc.prioridad')
            ->select('cds.id', 'cds.nro_docto', 'cds.nro_cuota', 'cd.descripcion', 'ccc.prioridad', 'cds.vlr_detalle', 'cds.vlr_cuentas_orden', 'cds.con_descuento')
            ->get();
    }

    public function aplicaValorLiquidacion(int $nro_docto, array $cuotas)
    {
        //dd($nro_docto, $cuotas);
        foreach ($cuotas as $cuota) {
            DB::table('cuotas_detalles')
                ->where('nro_docto', $nro_docto)
                ->where('id', $cuota['id'])
                ->update([
                    'vlr_cuentas_orden' => isset($cuota['vlr_aplicar']) ? $cuota['vlr_aplicar'] : 0.00
                ]);
        }

        Notification::make()
            ->title('Atención')
            ->icon('heroicon-m-check-circle')
            ->body('Valor aplicado correctamente')
            ->success()
            ->duration(5000)
            ->send();
    }

    public function vencimientoDescuento($vencimiento)
    {
        // Validamos que ya exista un registro en la base de datos
        $existe = DB::table('tmp_vencimiento_descuento')
            ->where('cliente', $vencimiento['cliente'])
            ->where('nro_docto', $vencimiento['nro_docto'])
            ->where('puc', $vencimiento['cuenta_contable'])
            ->where('codigo_concepto', $vencimiento['codigo_concepto'])
            ->first();

        if ($existe) {
            // Si existe, actualizamos el registro
            DB::table('tmp_vencimiento_descuento')
                ->where('id', $existe->id) // Usamos el ID del registro encontrado
                ->update([
                    'valor' => $vencimiento['valor']
                ]);
            return;
        }

        // Si no existe, insertamos un nuevo registro
        DB::table('tmp_vencimiento_descuento')->insert([
            'cliente' => $vencimiento['cliente'],
            'nro_docto' => $vencimiento['nro_docto'],
            'puc' => $vencimiento['cuenta_contable'],
            'valor' => $vencimiento['valor'],
            'codigo_concepto' => $vencimiento['codigo_concepto'],
            'descripcion' => $vencimiento['descripcion'],
        ]);

        return;
    }

    public function eliminaVencimiento($descuento)
    {
        DB::table('tmp_vencimiento_descuento')
            ->where('id', $descuento)
            ->delete();

        Notification::make()
            ->title('Atención')
            ->icon('heroicon-m-trash')
            ->body('Descuento eliminado correctamente')
            ->success()
            ->duration(5000)
            ->send();
    }

    public function generarComprobante()
    {
        // Validar campos
        if (!$this->cliente) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('Debe seleccionar un cliente')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        if (!$this->tipo_documento_id) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('Debe seleccionar un tipo de documento')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        if (!$this->tipo_pago) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('Debe seleccionar un tipo de pago')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        // Validate if all three values are empty
        if (
            ($this->efectivo === null || $this->efectivo === 0 || $this->efectivo === '') &&
            ($this->cheque === null || $this->cheque === 0 || $this->cheque === '') &&
            ($this->valor_abonar === null || $this->valor_abonar === 0 || $this->valor_abonar === '')
        ) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('Debe ingresar al menos un valor en EFECTIVO, CHEQUE y VALOR A ABONAR')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        /* DB::statement("CALL generar_comprobante(?, ?, ?, ?, ?, ?, ?)", [
            $this->cliente->tercero_id,
            $this->tipo_documento_id,
            $this->tipo_pago,
            $this->efectivo,
            $this->cheque,
            $this->valor_abonar,
            auth()->user()->name,
        ]); */

        DB::transaction(function (): void {
            try {
                $clienteId = $this->cliente->tercero_id;
                $siglaDocumento = TipoDocumentoContable::find($this->tipo_documento_id);
                $now = now()->format('Y-m-d');
                $usuario = auth()->user()->name;

                foreach ($this->cliente->carteraEncabezados->where('estado', 'A')->where('tdocto', 'PAG') as $carteraEncabezado) {
                    // Paso 1: Insertar en documentos_cancelaciones
                    DB::table('documentos_cancelaciones')->insert([
                        'tdocto' => $siglaDocumento->sigla,
                        'id_proveedor' => $carteraEncabezado->nro_docto,
                        'fecha_docto' => $now,
                        'cliente' => $clienteId,
                        'contabilizado' => 'N',
                        'con_nota_credito' => 1,
                        'moneda' => 1,
                        'vlr_pago_efectivo' => $this->efectivo ?: 0,
                        'vlr_pago_cheque' => $this->cheque ?: 0,
                        'vlr_descuento' => 0,
                        'usuario_crea' => $usuario,
                        'vlr_pago_otros' => $this->valor_abonar ?: 0,
                        'observaciones' => null
                    ]);

                    // Paso 2: Insertar en documentos_cancelaciones_detalles
                    $consecutivo = DB::table('documentos_cancelaciones_detalles')
                        ->where('numero_documento', $carteraEncabezado->nro_docto)
                        ->first();

                    if ($carteraEncabezado->vlr_congelada > 0) {
                        $this->procesarCreditosVigentes($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId);
                        $this->procesarObligaciones($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId);
                        $this->procesarOtrosConceptos($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId);
                    }

                    // Paso 3: Generar liquidaciones
                    $this->procesarLiquidaciones($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId);

                    // Paso 4: Generar contabilidad
                    $comprobante = $this->crearComprobante($siglaDocumento, $clienteId, $usuario, $now);
                    $this->crearLineasComprobante($comprobante, $carteraEncabezado, $siglaDocumento, $clienteId);

                    // Paso 5: Actualizar cuotas detalles
                    $this->actualizarCuotasDetalles($carteraEncabezado, $siglaDocumento, $comprobante);

                    // Paso 6: Actualizar cuota encabezado
                    $this->actualizarCuotaEncabezado($carteraEncabezado, $siglaDocumento, $comprobante);

                    // Paso 7: Actualizar cartera encabezado
                    $this->actualizarCarteraEncabezado($carteraEncabezado, $siglaDocumento, $comprobante);
                }

                // Notificación de finalizado
                Notification::make()
                    ->title('Atención')
                    ->icon('heroicon-m-check-circle')
                    ->body('Comprobante generado correctamente')
                    ->success()
                    ->duration(5000)
                    ->send();
            } catch (\Throwable $th) {
                throw $th;
            }
        }, 5);

        /* // Iniciamos la transacción de datos
        DB::transaction(function (): void {

            try {
                foreach ($this->cliente->carteraEncabezados->where('estado', 'A')->where('tdocto', 'PAG') as $carteraEncabezado) {

                    // 1. paso
                    $siglaDocumento = TipoDocumentoContable::where('id', $this->valor)->first();

                    DB::table('documentos_cancelaciones')->insert([
                        'tdocto' => $siglaDocumento->sigla,
                        'id_proveedor' => $carteraEncabezado->nro_docto,
                        'fecha_docto' => now()->format('Y-m-d'),
                        'cliente' => $this->cliente->tercero_id,
                        'contabilizado' => 'N',
                        'con_nota_credito' => 1,
                        'moneda' => 1,
                        'vlr_pago_efectivo' => $this->efectivo !== '' ? $this->efectivo : 0, // Ensure it's not an empty string
                        'vlr_pago_cheque' => $this->cheque !== '' ? $this->cheque : 0, // Ensure it's not an empty string
                        'vlr_descuento' => 0,
                        'usuario_crea' => auth()->user()->name,
                        'vlr_pago_otros' => $this->valor_abonar !== '' ? $this->valor_abonar : 0, // Ensure it's not an empty string
                        'observaciones' => null
                    ]);

                    // 2. paso (Opcion 2  y opcion 3)
                    $consecutivo = DB::table('documentos_cancelaciones_detalles')->where('numero_documento', $carteraEncabezado->nro_docto)->first();

                    if ($carteraEncabezado->vlr_congelada > 0) {


                        // Valores de creditos vigentes
                        DB::table('documentos_cancelaciones_detalles')->insert([
                            'tipo_documento' => $siglaDocumento->sigla,
                            'numero_documento' => $carteraEncabezado->nro_docto,
                            'consecutivo' => $consecutivo == null ? 1 : $consecutivo->consecutivo + 1,
                            'cliente_id' => $this->cliente->tercero_id,
                            'tipo_pago' => 'DRE',
                            'tipo_documento_dre' => 'PAG',
                            'numero_documento_dre' => $carteraEncabezado->nro_docto,
                            'valor_pago' => $carteraEncabezado->vlr_congelada,
                            'valor_descuento' => 0,
                            'servicio_concepto_lcd' => 1,
                            'tipo_recalculo' => 'C',
                            'concepto_descuento_lcd' => 1
                        ]);


                        DB::table('cartera_encabezados')->where('id', $carteraEncabezado->id)->update([
                            'vlr_reliquidado' => $carteraEncabezado->vlr_reliquidado += $carteraEncabezado->vlr_congelada
                        ]);

                        // Valores de obligaciones
                        $obligaciones = DB::table('detalle_vencimiento_descuento')
                            ->where('cliente', $this->cliente->tercero_id)
                            ->where('estado', 'A')
                            ->get();


                        foreach ($obligaciones as $obligacion) {
                            DB::table('documentos_cancelaciones_detalles')->insert([
                                'tipo_documento' => $siglaDocumento->sigla,
                                'numero_documento' => $carteraEncabezado->nro_docto,
                                'consecutivo' => $consecutivo == null ? 1 : $consecutivo->consecutivo + 1,
                                'cliente_id' => $this->cliente->tercero_id,
                                'tipo_pago' => 'VDE',
                                'concepto_descuento_vde' => $obligacion->con_descuento,
                                'consecutivo_vde' => $obligacion->consecutivo,
                                'numero_cuota_vde' => $obligacion->nro_cuota,
                                'valor_pago' => $obligacion->vlr_congelada,
                                'valor_descuento' => 0,
                                'servicio_concepto_lcd' => 1,
                            ]);

                            // limpiamos
                            DB::table('detalle_vencimiento_descuento')
                                ->where('id', $obligacion->id)
                                ->update([
                                    'vlr_congelada' => 0
                                ]);
                        }


                        // Valores de otros conceptos
                        $otrosConceptos = DB::table('tmp_vencimiento_descuento')
                            ->where('cliente', $this->cliente->tercero_id)
                            ->get();

                        if (count($otrosConceptos) > 1) {
                            foreach ($otrosConceptos as $otroConcepto) {
                                DB::table('documentos_cancelaciones_detalles')->insert([
                                    'tipo_documento' => $siglaDocumento->sigla,
                                    'numero_documento' => $carteraEncabezado->nro_docto,
                                    'consecutivo' => $consecutivo == null ? 1 : $consecutivo->consecutivo + 1,
                                    'cliente_id' => $this->cliente->tercero_id,
                                    'tipo_pago' => 'LCD',
                                    'concepto_descuento_lcd' => $otroConcepto->codigo_concepto,
                                    'valor_pago' => $otroConcepto->valor,
                                    'valor_descuento' => 0,
                                    'servicio_concepto_lcd' => 1,
                                ]);

                                DB::table('tmp_vencimiento_descuento')->where('id', $otroConcepto->id)->delete();
                            }
                        } else {
                            DB::table('documentos_cancelaciones_detalles')->insert([
                                'tipo_documento' => $siglaDocumento->sigla,
                                'numero_documento' => $carteraEncabezado->nro_docto,
                                'consecutivo' => $consecutivo == null ? 1 : $consecutivo->consecutivo + 1,
                                'cliente_id' => $this->cliente->tercero_id,
                                'tipo_pago' => 'LCD',
                                'concepto_descuento_lcd' => $otrosConceptos[0]->codigo_concepto,
                                'valor_pago' => $otrosConceptos[0]->valor,
                                'valor_descuento' => 0,
                                'servicio_concepto_lcd' => 1,
                            ]);

                            DB::table('tmp_vencimiento_descuento')->where('id', $otrosConceptos[0]->id)->delete();
                        }
                    }

                    // 3. paso ( opcion 1)
                    $liquidaciones = $this->generarLiquidacion($carteraEncabezado->nro_docto);
                    foreach ($liquidaciones as $liquidacion) {
                        if ($liquidacion->vlr_cuentas_orden > 0) {
                            DB::table('documentos_cancelaciones_detalles')->insert([
                                'tipo_documento' => $siglaDocumento->sigla,
                                'numero_documento' => $carteraEncabezado->nro_docto,
                                'consecutivo' => $consecutivo == null ? 1 : $consecutivo->consecutivo + 1,
                                'cliente_id' => $this->cliente->tercero_id,
                                'tipo_pago' => 'DVT',
                                'tipo_documento_dvt' => 'PAG',
                                'numero_documento_dvt' => $liquidacion->nro_docto,
                                'numero_cuota_dvt' => $liquidacion->nro_cuota,
                                'consecutivo_dvt' => 1,
                                'concepto_descuento_dvt' => $liquidacion->con_descuento,
                                'valor_pago' => $liquidacion->vlr_cuentas_orden,
                                'valor_descuento' => 0,
                                'servicio_concepto_lcd' => 1,
                                'tipo_recalculo' => 'N'
                            ]);
                        }
                    }

                    // 4. paso (Generacion de contabilidad)

                    // Creamos el comprobante contable
                    $comprobante = Comprobante::create([
                        'fecha_comprobante' => now()->format('Y-m-d'),
                        'tercero_id' => $this->cliente->id,
                        'tipo_documento_contables_id' => $siglaDocumento->id,
                        'n_documento' => $siglaDocumento->numerador,
                        'descripcion_comprobante' => 'Cancelacion cliente ' . $this->cliente->tercero_id . ' ' . $this->cliente->nombres,
                        'fecha_comprobante' => now()->format('Y-m-d'),
                        'estado' => 'Activo',
                        'usuario_original' => auth()->user()->name
                    ]);

                    // Creamos las lineas del comprobante
                    $lineas = DB::table('documentos_cancelaciones_detalles')->where('numero_documento', $carteraEncabezado->nro_docto)->get();

                    // Buscamos la linea para saber el tipo de inversion y tipo garantia
                    $lineaCredito = CreditoLinea::where('id', $carteraEncabezado->linea)->first();
                    $this->cuentaCapital = DB::table('parametro_cartera_vigencias')
                        ->where('tipo_garantia', $lineaCredito->tipo_garantia_id)
                        ->where('tipo_inversion', $lineaCredito->tipo_inversion_id)
                        ->where('categoria', $carteraEncabezado->categoria_actual)
                        ->where('modo_desembolso', $carteraEncabezado->forma_descuento)
                        ->first();

                    $ultimaLinea = ComprobanteLinea::where('comprobante_id', $comprobante->id)->orderBy('linea', 'desc')->first();
                    foreach ($lineas as $linea) {
                        $conceptoDescuento = $linea->concepto_descuento_dvt ?? $linea->concepto_descuento_vde ?? $linea->concepto_descuento_lcd;

                        if($conceptoDescuento === null){
                            dd($linea);
                        }
                        ComprobanteLinea::create([
                            'pucs_id' => $this->validaCuentaContable($conceptoDescuento),
                            'tercero_id' => $this->cliente->id,
                            'descripcion_linea' => DB::table('concepto_descuentos')->where('codigo_descuento', $conceptoDescuento)->first()->descripcion,
                            'debito' => 0,
                            'credito' => $linea->valor_pago,
                            'comprobante_id' => $comprobante->id,
                            'linea' => $ultimaLinea ? $ultimaLinea->linea + 1 : 1
                        ]);
                    }

                    // Se crea una contrapartida para el comprobante
                    ComprobanteLinea::create([
                        'pucs_id' => Puc::where('puc', $this->tipo_pago)->first()->id,
                        'tercero_id' => $this->cliente->id,
                        'descripcion_linea' => 'Cuenta por cobrar cliente ' . $this->cliente->tercero_id . ' ' . $this->cliente->nombres,
                        'debito' => $lineas->sum('valor_pago'),
                        'credito' => 0,
                        'comprobante_id' => $comprobante->id,
                        'linea' => $ultimaLinea ? $ultimaLinea->linea + 1 : 1
                    ]);


                    // 4. paso (Actualizar cuotas detalles)
                    $cuotas_detalles = DB::table('cuotas_detalles')
                        ->where('tdocto', 'PAG')
                        ->where('nro_docto', $carteraEncabezado->nro_docto)
                        ->where('estado', 'A')
                        ->get();

                    foreach ($cuotas_detalles as $cuota) {

                        if ($siglaDocumento->sigla === 'NCR') {
                            DB::table('cuotas_detalles')
                                ->where('id', $cuota->id)
                                ->update([
                                    'vlr_abono_ncr' => $cuota->vlr_cuentas_orden
                                ]);

                            DB::table('cuotas_encabezados')
                                ->where('nro_docto', $carteraEncabezado->nro_docto)
                                ->where('tdocto', 'PAG')
                                ->where('estado', 'A')
                                ->update([
                                    'vlr_abono_ncr' => $cuota->vlr_cuentas_orden
                                ]);
                        } else {
                            DB::table('cuotas_detalles')
                                ->where('id', $cuota->id)
                                ->update([
                                    'vlr_abono_rec' => $cuota->vlr_cuentas_orden
                                ]);

                            DB::table('cuotas_encabezados')
                                ->where('nro_docto', $carteraEncabezado->nro_docto)
                                ->where('tdocto', 'PAG')
                                ->where('estado', 'A')
                                ->update([
                                    'vlr_abono_rec' => $cuota->vlr_cuentas_orden
                                ]);
                        }

                        $sumatoria = $cuota->vlr_abono_ncr + $cuota->vlr_abono_rec + $cuota->vlr_abono_dpa + $cuota->vlr_descuento;

                        if ($sumatoria == $cuota->vlr_detalle) {
                            DB::table('cuotas_detalles')
                                ->where('id', $cuota->id)
                                ->update([
                                    'estado' => 'C',
                                    'fecha_pago_total' => $comprobante->fecha_comprobante
                                ]);
                        }
                    }


                    // 5. actualizar cuota encabezado
                    $cuota_encabezado = DB::table('cuotas_encabezados')
                        ->where('tdocto', 'PAG')
                        ->where('nro_docto', $carteraEncabezado->nro_docto)
                        ->where('estado', 'A')
                        ->get();

                    if ($cuota_encabezado->count() > 1) {
                        $cuota_encabezado = $cuota_encabezado[0];
                        $sumatoria = $cuota_encabezado->vlr_abono_ncr + $cuota_encabezado->vlr_abono_rec + $cuota_encabezado->vlr_abono_dpa + $cuota_encabezado->vlr_descuento;

                        if ($sumatoria == $cuota_encabezado->vlr_cuota) {
                            DB::table('cuotas_encabezados')
                                ->where('id', $cuota_encabezado->id)
                                ->update([
                                    'estado' => 'C',
                                    'fecha_pago_total' => $comprobante->fecha_comprobante
                                ]);
                        }
                    } else {
                        foreach ($cuota_encabezado as $cuota) {
                            $sumatoria = $cuota->vlr_abono_ncr + $cuota->vlr_abono_rec + $cuota->vlr_abono_dpa + $cuota->vlr_descuento;

                            if ($sumatoria == $cuota->vlr_cuota) {
                                DB::table('cuotas_encabezados')
                                    ->where('id', $cuota->id)
                                    ->update([
                                        'estado' => 'C',
                                        'fecha_pago_total' => $comprobante->fecha_comprobante
                                    ]);
                            }
                        }
                    }


                    // 6. Actualizar cartera encabezado
                    if ($siglaDocumento->sigla === 'NCR') {
                        $sumatoria = $cuotas_detalles->where('con_descuento', 1)->sum('vlr_abono_ncr');
                        $carteraEncabezado->update([
                            'vlr_abono_ncr' => $carteraEncabezado->vlr_abono_ncr += $sumatoria
                        ]);
                    } else {
                        $sumatoria = $cuotas_detalles->where('con_descuento', 1)->sum('vlr_abono_rec');
                        $carteraEncabezado->update([
                            'vlr_abono_rec' => $carteraEncabezado->vlr_abono_rec += $sumatoria
                        ]);
                    }

                    $sumatoria = $carteraEncabezado->vlr_abono_ncr + $carteraEncabezado->vlr_abono_rec + $carteraEncabezado->vlr_abono_dpa + $carteraEncabezado->vlr_reliquidado;
                    if ($sumatoria == $carteraEncabezado->vlr_docto_vto) {
                        $carteraEncabezado->update([
                            'estado' => 'C',
                            'fecha_pago_total' => $comprobante->fecha_comprobante
                        ]);
                    }

                    // 7. Actualizar cartera composicion conceptos
                    $carteraEncabezado->update([
                        'vlr_docto_vto' => $comprobante->vlr_docto_vto - $sumatoria
                    ]);
                }
            } catch (\Throwable $th) {
                throw $th;
            }



            // Mostramos notificacion de finalizado
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-check-circle')
                ->body('Comprobante generado correctamente')
                ->success()
                ->duration(5000)
                ->send();
        }, 5); */
    }

    public function generarComprobanteV2()
    {
        // Validar campos
        if (!$this->cliente) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('Debe seleccionar un cliente')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        if (!$this->tipo_documento_id) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('Debe seleccionar un tipo de documento')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        if (!$this->tipo_pago) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('Debe seleccionar un tipo de pago')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        // Validate if all three values are empty
        if (
            ($this->efectivo === null || $this->efectivo === 0 || $this->efectivo === '') &&
            ($this->cheque === null || $this->cheque === 0 || $this->cheque === '') &&
            ($this->valor_abonar === null || $this->valor_abonar === 0 || $this->valor_abonar === '')
        ) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('Debe ingresar al menos un valor en EFECTIVO, CHEQUE y VALOR A ABONAR')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        DB::transaction(function (): void {
            try {
                $clienteId = $this->cliente->tercero_id;
                $siglaDocumento = TipoDocumentoContable::find($this->tipo_documento_id);
                $now = now()->format('Y-m-d');
                $usuario = auth()->user()->name;

                foreach ($this->cliente->carteraEncabezados->where('estado', 'A')->where('tdocto', 'PAG') as $carteraEncabezado) {
                    // Paso 1: Insertar en documentos_cancelaciones
                    DB::table('documentos_cancelaciones')->insert([
                        'tdocto' => $siglaDocumento->sigla,
                        'id_proveedor' => $carteraEncabezado->nro_docto,
                        'fecha_docto' => $now,
                        'cliente' => $clienteId,
                        'contabilizado' => 'N',
                        'con_nota_credito' => 1,
                        'moneda' => 1,
                        'vlr_pago_efectivo' => $this->efectivo ?: 0,
                        'vlr_pago_cheque' => $this->cheque ?: 0,
                        'vlr_descuento' => 0,
                        'usuario_crea' => $usuario,
                        'vlr_pago_otros' => $this->valor_abonar ?: 0,
                        'observaciones' => null
                    ]);

                    // Paso 2: Insertar en documentos_cancelaciones_detalles
                    $consecutivo = DB::table('documentos_cancelaciones_detalles')
                        ->where('numero_documento', $carteraEncabezado->nro_docto)
                        ->first();

                    if ($carteraEncabezado->vlr_congelada > 0) {
                        $this->procesarCreditosVigentes($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId);
                        $this->procesarObligaciones($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId);
                        $this->procesarOtrosConceptos($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId);
                    }

                    // Paso 3: Generar liquidaciones
                    $this->procesarLiquidaciones($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId);

                    // Paso 4: Generar contabilidad
                    $comprobante = $this->crearComprobante($siglaDocumento, $clienteId, $usuario, $now);
                    $this->crearLineasComprobante($comprobante, $carteraEncabezado, $siglaDocumento, $clienteId);

                    // Paso 5: Actualizar cuotas detalles
                    $this->actualizarCuotasDetalles($carteraEncabezado, $siglaDocumento, $comprobante);

                    // Paso 6: Actualizar cuota encabezado
                    $this->actualizarCuotaEncabezado($carteraEncabezado, $siglaDocumento, $comprobante);

                    // Paso 7: Actualizar cartera encabezado
                    $this->actualizarCarteraEncabezado($carteraEncabezado, $siglaDocumento, $comprobante);
                }

                // Notificación de finalizado
                Notification::make()
                    ->title('Atención')
                    ->icon('heroicon-m-check-circle')
                    ->body('Comprobante generado correctamente')
                    ->success()
                    ->duration(5000)
                    ->send();
            } catch (\Throwable $th) {
                throw $th;
            }
        }, 5);
    }

    // Métodos auxiliares para mejorar la legibilidad y reutilización
    private function procesarCreditosVigentes($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId)
    {
        DB::table('documentos_cancelaciones_detalles')->insert([
            'tipo_documento' => $siglaDocumento->sigla,
            'numero_documento' => $carteraEncabezado->nro_docto,
            'consecutivo' => $consecutivo ? $consecutivo->consecutivo + 1 : 1,
            'cliente_id' => $clienteId,
            'tipo_pago' => 'DRE',
            'tipo_documento_dre' => 'PAG',
            'numero_documento_dre' => $carteraEncabezado->nro_docto,
            'valor_pago' => $carteraEncabezado->vlr_congelada,
            'valor_descuento' => 0,
            'servicio_concepto_lcd' => 1,
            'tipo_recalculo' => 'C',
            'concepto_descuento_lcd' => 1
        ]);

        DB::table('cartera_encabezados')->where('id', $carteraEncabezado->id)->update([
            'vlr_reliquidado' => $carteraEncabezado->vlr_reliquidado + $carteraEncabezado->vlr_congelada
        ]);
    }

    private function procesarObligaciones($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId)
    {
        $obligaciones = DB::table('detalle_vencimiento_descuento')
            ->where('cliente', $clienteId)
            ->where('estado', 'A')
            ->get();

        foreach ($obligaciones as $obligacion) {
            DB::table('documentos_cancelaciones_detalles')->insert([
                'tipo_documento' => $siglaDocumento->sigla,
                'numero_documento' => $carteraEncabezado->nro_docto,
                'consecutivo' => $consecutivo ? $consecutivo->consecutivo + 1 : 1,
                'cliente_id' => $clienteId,
                'tipo_pago' => 'VDE',
                'concepto_descuento_vde' => $obligacion->con_descuento,
                'consecutivo_vde' => $obligacion->consecutivo,
                'numero_cuota_vde' => $obligacion->nro_cuota,
                'valor_pago' => $obligacion->vlr_congelada,
                'valor_descuento' => 0,
                'servicio_concepto_lcd' => 1,
            ]);

            DB::table('detalle_vencimiento_descuento')
                ->where('id', $obligacion->id)
                ->update(['vlr_congelada' => 0]);
        }
    }

    private function procesarOtrosConceptos($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId)
    {
        $otrosConceptos = DB::table('tmp_vencimiento_descuento')
            ->where('cliente', $clienteId)
            ->get();

        foreach ($otrosConceptos as $otroConcepto) {
            DB::table('documentos_cancelaciones_detalles')->insert([
                'tipo_documento' => $siglaDocumento->sigla,
                'numero_documento' => $carteraEncabezado->nro_docto,
                'consecutivo' => $consecutivo ? $consecutivo->consecutivo + 1 : 1,
                'cliente_id' => $clienteId,
                'tipo_pago' => 'LCD',
                'concepto_descuento_lcd' => $otroConcepto->codigo_concepto,
                'valor_pago' => $otroConcepto->valor,
                'valor_descuento' => 0,
                'servicio_concepto_lcd' => 1,
            ]);

            DB::table('tmp_vencimiento_descuento')->where('id', $otroConcepto->id)->delete();
        }
    }

    private function procesarLiquidaciones($carteraEncabezado, $siglaDocumento, $consecutivo, $clienteId)
    {
        $liquidaciones = $this->generarLiquidacion($carteraEncabezado->nro_docto);
        foreach ($liquidaciones as $liquidacion) {
            if ($liquidacion->vlr_cuentas_orden > 0) {
                DB::table('documentos_cancelaciones_detalles')->insert([
                    'tipo_documento' => $siglaDocumento->sigla,
                    'numero_documento' => $carteraEncabezado->nro_docto,
                    'consecutivo' => $consecutivo ? $consecutivo->consecutivo + 1 : 1,
                    'cliente_id' => $clienteId,
                    'tipo_pago' => 'DVT',
                    'tipo_documento_dvt' => 'PAG',
                    'numero_documento_dvt' => $liquidacion->nro_docto,
                    'numero_cuota_dvt' => $liquidacion->nro_cuota,
                    'consecutivo_dvt' => 1,
                    'concepto_descuento_dvt' => $liquidacion->con_descuento,
                    'valor_pago' => $liquidacion->vlr_cuentas_orden,
                    'valor_descuento' => 0,
                    'servicio_concepto_lcd' => 1,
                    'tipo_recalculo' => 'N'
                ]);
            }
        }
    }

    private function crearComprobante($siglaDocumento, $clienteId, $usuario, $now)
    {
        return Comprobante::create([
            'fecha_comprobante' => $now,
            'tercero_id' => $this->cliente->id,
            'tipo_documento_contables_id' => $siglaDocumento->id,
            'n_documento' => $siglaDocumento->numerador,
            'descripcion_comprobante' => 'Cancelacion cliente ' . $clienteId . ' ' . $this->cliente->nombres,
            'estado' => 'Activo',
            'usuario_original' => $usuario
        ]);
    }

    private function crearLineasComprobante($comprobante, $carteraEncabezado, $siglaDocumento, $clienteId)
    {
        $lineas = DB::table('documentos_cancelaciones_detalles')
            ->where('numero_documento', $carteraEncabezado->nro_docto)
            ->get();

        $ultimaLinea = ComprobanteLinea::where('comprobante_id', $comprobante->id)
            ->orderBy('linea', 'desc')
            ->first();

        $lineaCredito = CreditoLinea::where('id', $carteraEncabezado->linea)->first();
        $this->cuentaCapital = DB::table('parametro_cartera_vigencias')
            ->where('tipo_garantia', $lineaCredito->tipo_garantia_id)
            ->where('tipo_inversion', $lineaCredito->tipo_inversion_id)
            ->where('categoria', $carteraEncabezado->categoria_actual)
            ->where('modo_desembolso', $carteraEncabezado->forma_descuento)
            ->first();

        foreach ($lineas as $linea) {
            $conceptoDescuento = $linea->concepto_descuento_dvt ?? $linea->concepto_descuento_vde ?? $linea->concepto_descuento_lcd;
            ComprobanteLinea::create([
                'pucs_id' => $this->validaCuentaContable($conceptoDescuento),
                'tercero_id' => $this->cliente->id,
                'descripcion_linea' => DB::table('concepto_descuentos')->where('codigo_descuento', $conceptoDescuento)->first()->descripcion,
                'debito' => 0,
                'credito' => $linea->valor_pago,
                'comprobante_id' => $comprobante->id,
                'linea' => $ultimaLinea ? $ultimaLinea->linea + 1 : 1
            ]);
        }

        ComprobanteLinea::create([
            'pucs_id' => Puc::where('puc', $this->tipo_pago)->first()->id,
            'tercero_id' => $this->cliente->id,
            'descripcion_linea' => 'Cuenta por cobrar cliente ' . $clienteId . ' ' . $this->cliente->nombres,
            'debito' => $lineas->sum('valor_pago'),
            'credito' => 0,
            'comprobante_id' => $comprobante->id,
            'linea' => $ultimaLinea ? $ultimaLinea->linea + 1 : 1
        ]);
    }

    private function actualizarCuotasDetalles($carteraEncabezado, $siglaDocumento, $comprobante)
    {
        $cuotasDetalles = DB::table('cuotas_detalles')
            ->where('tdocto', 'PAG')
            ->where('nro_docto', $carteraEncabezado->nro_docto)
            ->where('estado', 'A')
            ->get();

        foreach ($cuotasDetalles as $cuota) {
            $campoAbono = $siglaDocumento->sigla === 'NCR' ? 'vlr_abono_ncr' : 'vlr_abono_rec';
            DB::table('cuotas_detalles')
                ->where('id', $cuota->id)
                ->update([$campoAbono => $cuota->vlr_cuentas_orden]);

            DB::table('cuotas_encabezados')
                ->where('nro_docto', $carteraEncabezado->nro_docto)
                ->where('tdocto', 'PAG')
                ->where('estado', 'A')
                ->update([$campoAbono => $cuota->vlr_cuentas_orden]);

            $sumatoria = $cuota->vlr_abono_ncr + $cuota->vlr_abono_rec + $cuota->vlr_abono_dpa + $cuota->vlr_descuento;

            if ($sumatoria == $cuota->vlr_detalle) {
                DB::table('cuotas_detalles')
                    ->where('id', $cuota->id)
                    ->update([
                        'estado' => 'C',
                        'fecha_pago_total' => $comprobante->fecha_comprobante
                    ]);
            }
        }
    }

    private function actualizarCuotaEncabezado($carteraEncabezado, $siglaDocumento, $comprobante)
    {
        $cuotaEncabezado = DB::table('cuotas_encabezados')
            ->where('tdocto', 'PAG')
            ->where('nro_docto', $carteraEncabezado->nro_docto)
            ->where('estado', 'A')
            ->first();

        if ($cuotaEncabezado) {
            $sumatoria = $cuotaEncabezado->vlr_abono_ncr + $cuotaEncabezado->vlr_abono_rec + $cuotaEncabezado->vlr_abono_dpa + $cuotaEncabezado->vlr_descuento;

            if ($sumatoria == $cuotaEncabezado->vlr_cuota) {
                DB::table('cuotas_encabezados')
                    ->where('id', $cuotaEncabezado->id)
                    ->update([
                        'estado' => 'C',
                        'fecha_pago_total' => $comprobante->fecha_comprobante
                    ]);
            }
        }
    }

    private function actualizarCarteraEncabezado($carteraEncabezado, $siglaDocumento, $comprobante)
    {
        $campoAbono = $siglaDocumento->sigla === 'NCR' ? 'vlr_abono_ncr' : 'vlr_abono_rec';
        $sumatoria = DB::table('cuotas_detalles')
            ->where('con_descuento', 1)
            ->sum($campoAbono);

        $carteraEncabezado->update([$campoAbono => $carteraEncabezado->$campoAbono + $sumatoria]);

        $sumatoriaTotal = $carteraEncabezado->vlr_abono_ncr + $carteraEncabezado->vlr_abono_rec + $carteraEncabezado->vlr_abono_dpa + $carteraEncabezado->vlr_reliquidado;

        if ($sumatoriaTotal == $carteraEncabezado->vlr_docto_vto) {
            $carteraEncabezado->update([
                'estado' => 'C',
                'fecha_pago_total' => $comprobante->fecha_comprobante
            ]);
        }

        $carteraEncabezado->update([
            'vlr_docto_vto' => $carteraEncabezado->vlr_docto_vto - $sumatoriaTotal
        ]);
    }

    public function validaCuentaContable($concepto): int
    {
        switch ($concepto) {
            case 1:
                return Puc::where('puc', $this->cuentaCapital->puc_contable)->first()->id;
                break;
            default:
                $cuenta_contable = DB::table('concepto_descuentos')->where('codigo_descuento', $concepto)->first()->cuenta_contable;
                return Puc::where('puc', $cuenta_contable)->first()->id;
                break;
        }
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
