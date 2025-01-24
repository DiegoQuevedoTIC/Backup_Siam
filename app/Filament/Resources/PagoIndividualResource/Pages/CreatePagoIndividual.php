<?php

namespace App\Filament\Resources\PagoIndividualResource\Pages;

use App\Filament\Resources\PagoIndividualResource;
use App\Models\Tercero;
use App\Models\TipoDocumentoContable;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;
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
    public $nro_docto_actual;
    public $pendiente;
    public $valor;


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
                    ->columnSpan(2)
                    ->searchable()
                    ->required(function (Get $get, Set $set) {
                        $this->valor = $get('tipo_documento');
                        if ($this->valor) {
                            $numerador = TipoDocumentoContable::where('id', $this->valor)->first()->numerador;
                            $set('nro_documento', $numerador);
                        }
                        return false;
                    }),
                TextInput::make('nro_documento')
                    ->label('Nro de documento')
                    ->disabled(),
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
                                        ->sum('vlr_congelada');

                                    $otros_conceptos = DB::table('tmp_vencimiento_descuento')
                                        ->where('cliente', $this->cliente->tercero_id)
                                        ->sum('valor');

                                    $total_a_pagar = $sumatoria + $otros_conceptos + $this->aplica_valor_a_total;
                                    $set('total_a_pagar', $total_a_pagar);
                                }

                                return false;
                            }),
                    ])->columns(3)
            ])
            ->columns(4);
    }

    public function updateValorAplicado($nuevo_valor, Get $get, Set $set)
    {
        (float)$this->aplica_valor_a_total += $nuevo_valor;
    }

    public function calcularIntereses(int $nro_docto, Get $get)
    {
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

        // Validamos que haya al menos una cuota
        if ($cuotasEncabezado->isEmpty()) {
            Notification::make()
                ->title('Atención')
                ->icon('heroicon-m-exclamation-circle')
                ->body('No se encontraron cuotas para el documento proporcionado.')
                ->warning()
                ->duration(5000)
                ->send();

            return;
        }

        // Tomamos la fecha de vencimiento de la primera cuota
        $primeraFechaVencimiento = Carbon::parse($cuotasEncabezado[0]->fecha_vencimiento);

        $interes = 0;

        foreach ($cuotasEncabezado as $cuota) {
            // Calculamos los días de mora con base en la primera fecha
            $diasMora = $primeraFechaVencimiento->diffInDays(Carbon::parse(now()));

            // Calculamos el interés para esta cuota
            $interes += $cuota->vlr_cuota * ($diasMora * $cuota->interes_mora);
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
        //dd($nro_docto, $valorAplicado);

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
            ->select('cds.id', 'cds.nro_docto', 'cds.nro_cuota', 'cd.descripcion', 'ccc.prioridad', 'cds.vlr_detalle', 'cds.vlr_cuentas_orden')
            ->get();
    }

    public function aplicaValorLiquidacion(int $nro_docto, array $cuotas)
    {
        foreach ($cuotas as $cuota) {
            DB::table('cuotas_detalles')
                ->where('nro_docto', $nro_docto)
                ->where('id', $cuota['id'])
                ->update([
                    'vlr_cuentas_orden' => $cuota['vlr_aplicar']
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

    public function generarComprobante($nro_docto)
    {
        // Iniciamos la transacción de datos

        DB::transaction(function() use ($nro_docto) : void {

            // 1. paso

            DB::table('documento_cancelaciones')->insert([
                'tdocto' => TipoDocumentoContable::where('id', $this->valor)->first()->sigla,
                'id_proveedor' => $nro_docto,
                'fecha_docto' => now()->format('Y-m-d'),
                'cliente' => $this->cliente->tercero_id,
                'contabilizado' => 'N',
                'con_nota_credito' => 1,
                'moneda' => 1,
                'vlr_pago_efectivo' => $this->efectivo,
                'vlr_pago_cheque' => $this->cheque,
                'vlr_descuento' => 0,
                'usuario_crea' => auth()->user()->name,
                'vlr_pago_otros' => $this->valor_abonar,
                'observaciones' =>  null
            ]);


            // 2. paso

            DB::table('documento_cancelaciones_detalles')->insert([

            ]);



        }, 5);
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
