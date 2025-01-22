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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo_documento')
                    ->label('Tipo de documento')
                    ->options(TipoDocumentoContable::query()
                        ->select(DB::raw("id, CONCAT(sigla, ' - ', tipo_documento) AS nombre_completo"))
                        ->pluck('nombre_completo', 'id'))
                    ->live()
                    ->columnSpan(2)
                    ->searchable()
                    ->required(function (Get $get, Set $set) {
                        $valor = $get('tipo_documento');
                        if ($valor) {
                            $numerador = TipoDocumentoContable::where('id', $valor)->first()->numerador;
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
                            ->default(function (Get $get){
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
                            ->default(function (Get $get){
                                $this->cheque = $get('cheque');
                                return 0;
                            }),
                        TextInput::make('valor_abonar')
                            ->placeholder('Valor a abonar')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$')
                            ->live(onBlur: true)
                            ->default(function (Get $get){
                                $this->valor_abonar = $get('valor_abonar');
                                return 0;
                            }),
                        TextInput::make('valor_total')
                            ->placeholder('Valor total')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$')
                            ->readOnly()
                            ->default(function (Get $get, Set $set){
                                $e = $get('efectivo');
                                $c = $get('cheque');
                                $va = $get('valor_abonar');
                                $vt = $e + $c + $va;
                                $set('valor_total', $vt);
                                return 0;
                            }),
                        TextInput::make('pendiente_por_aplicar')
                            ->placeholder('Pendiente por aplicar')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$'),
                        TextInput::make('total_a_pagar')
                            ->placeholder('Total a pagar')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->prefix('$'),
                    ])->columns(3)
            ])
            ->columns(4);
    }

    public function updateValorAplicado($valor)
    {
        //dd($valor);
    }

    public function calcularIntereses($nro_docto, Get $get)
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

    public function aplicarValor($nro_docto, $valorAplicado)
    {
        //dd($nro_docto, $valorAplicado);

        DB::table('cartera_encabezados')->where('nro_docto', $nro_docto)->update([
            'vlr_congelada' => intval($valorAplicado)
        ]);

        Notification::make()
            ->title('Atención')
            ->icon('heroicon-m-check-circle')
            ->body('Valor aplicado correctamente')
            ->success()
            ->duration(5000)
            ->send();
    }


    public function generarLiquidacion($nro_docto)
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
            ->select('cds.nro_docto', 'cds.nro_cuota', 'cd.descripcion', 'ccc.prioridad', 'cds.vlr_detalle')
            ->get();
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
