<?php

namespace App\Filament\Resources\PagoIndividualResource\Pages;

use App\Filament\Resources\PagoIndividualResource;
use App\Models\Tercero;
use App\Models\TipoDocumentoContable;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\DB;

class CreatePagoIndividual extends CreateRecord
{
    protected static string $resource = PagoIndividualResource::class;
    protected static string $view = 'custom.tesoreria.create-pagos-individual';

    public bool $show = false;
    public $cliente;
    public $concepto_descuento;

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
                TextInput::make('efectivo')
                    ->placeholder('Monto efectivo')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->inputMode('decimal')
                    ->prefix('$'),
                TextInput::make('cheque')
                    ->placeholder('Nro cheque')
                    ->prefixIcon('heroicon-c-credit-card'),
            ])
            ->columns(4);
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
