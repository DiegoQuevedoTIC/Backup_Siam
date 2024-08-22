<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

use App\Exports\ComprobanteLineasExport;
use App\Filament\Resources\ComprobanteResource;
use App\Models\Comprobante;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use App\Models\TipoDocumentoContable;
use App\Models\Puc;
use App\Models\Tercero;
use App\Models\TipoContribuyente;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Actions as ActionsForm;
use Filament\Forms\Components\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

class EditComprobante extends EditRecord
{
    protected static string $resource = ComprobanteResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    public function form(Form $form): Form
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
                   DatePicker::make('fecha_comprobante')
                    ->label('Fecha de comprobante')
                    ->required()
                    ->columnSpan(2)
                    ->native(false)
                    ->disabled(function (Get $get, Set $set): bool {
                        $id = $get('tipo_documento_contables_id');
                        if (!is_null($id)) {
                            $isDateModified = TipoDocumentoContable::all()->find($id)->toArray()['fecha_modificable'];
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
                    ->columnSpan(3)
                    ->options($tipoDocumento)
                    ->required()
                    ->native(false)
                    ->live(),

                TextInput::make('n_documento')
                    ->label('Nº de Documento')
                    ->columnSpan(2)
                    ->rule('regex:/^[0-9]+$/')
                    ->required(),
                Select::make('tercero_id')
                    ->label('Tercero Comprobante')
                    ->required()
                    ->columnSpan(3)
                    ->native(false)
                    ->relationship('tercero', 'tercero_id')
                    ->markAsRequired(false)
                    ->searchable(),

                Textarea::make('descripcion_comprobante')
                    ->label('Descripcion del Comprobante')
                    ->columnSpan(8)
                    ->required(),

                    Section::make('Detalles')
                    ->schema(function () use ($puc) {
                        $data = DB::table('comprobante_lineas')
                            ->where('comprobante_id', $this->getRecord()->id)
                            ->count();

                        $array = [];
                        $limite = 30;

                        if ($data <= $limite) {
                            array_push(
                                $array,

                                TableRepeater::make('detalle')
                                    ->label('Detalle comprobante')
                                    ->relationship('comprobanteLinea')
                                    ->schema([
                                        Select::make('pucs_id')
                                            ->label('Cuenta PUC')
                                            ->options(['asd' => 'descripcion_comprobante'])
                                            ->live()
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
                                            ->mask(RawJs::make('$money($input)'))
                                            ->numeric()
                                            ->prefix('$')
                                            ->disabled(function (Get $get): bool {
                                                $query = Puc::where('id', $get('pucs_id'))->first();
                                                if (!is_null($query)) {
                                                    if ($query['naturaleza'] != 'D') {
                                                        return true;
                                                    }
                                                }
                                                return false;
                                            }),

                                        TextInput::make('credito')
                                            ->placeholder('Credito')
                                            ->numeric()
                                            ->inputMode('decimal')
                                            ->prefix('$')
                                            ->disabled(function (Get $get): bool {
                                                $query = Puc::find($get('pucs_id'));
                                                if (!is_null($query)) {
                                                    $query = $query->toArray();
                                                    if ($query['naturaleza'] != 'C') {
                                                        return true;
                                                    }
                                                }
                                                return false;
                                            }),
                                    ])
                                    ->reorderable()
                                    ->cloneable()
                                    ->collapsible()
                                    ->defaultItems(1)
                                    ->columnSpanFull(),
                            );
                        } else {

                            array_push(
                                $array,
                                ActionsForm::make([
                                    Action::make('export')
                                        ->label('Exportar EXCEL')
                                        ->color('primary')
                                        ->icon('heroicon-c-arrow-down-on-square')
                                        ->action(function () {
                                            $nameFile = $this->getRecord()->descripcion_comprobante . '.xlsx';
                                            return Excel::download(new ComprobanteLineasExport($this->getRecord()->id), $nameFile, \Maatwebsite\Excel\Excel::XLSX);
                                        })->after(function () {
                                            Notification::make()
                                                ->title('Se exporto la información de manera correcta.')
                                                ->icon('heroicon-m-check-circle')
                                                ->body('Los datos exportados correctamente')
                                                ->success()
                                                ->color('primary')
                                                ->send();
                                        }),

                                    Action::make('export_csv')
                                        ->label('Exportar CSV')
                                        ->color('primary')
                                        ->icon('heroicon-c-arrow-down-on-square')
                                        ->action(function () {
                                            $nameFile = $this->getRecord()->descripcion_comprobante . '.csv';
                                            return Excel::download(new ComprobanteLineasExport($this->getRecord()->id), $nameFile, \Maatwebsite\Excel\Excel::CSV);
                                        })->after(function () {
                                            Notification::make()
                                                ->title('Se exporto la información de manera correcta.')
                                                ->icon('heroicon-m-check-circle')
                                                ->body('Los datos exportados correctamente')
                                                ->success()
                                                ->color('primary')
                                                ->send();
                                        }),
                                ])->alignCenter()
                            );
                        }

                        return $array;
                    }),
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (array_key_exists('usar_plantilla', $data)) {
            unset($data['usar_plantilla']);
            unset($data['plantilla']);
        }
        if (!array_key_exists('fecha_comprobante', $data)) {
            $data['fecha_comprobante'] = date('Y-m-d');
            return $data;
        } else {
            return $data;
        }
    }

    protected function beforeSave(): void
    {
        $data = $this->data;
        $credito = array();
        $debito = array();
        foreach ($data['detalle'] as $key => $value) {
            if ($value['debito'] == '') {
                $credito[] = floatval($value['credito']);
            } else {
                $debito[] = floatval($value['debito']);
            }
        }

        if ((array_sum($credito) - array_sum($debito)) != 0.0) {
            Notification::make()
                ->title('No puede guardar un comprobante desbalanceado')
                ->danger()
                ->send();
            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
