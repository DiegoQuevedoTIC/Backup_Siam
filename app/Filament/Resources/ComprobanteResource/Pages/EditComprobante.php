<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

use App\Exports\ComprobanteExport;
use App\Exports\ComprobanteLineasExport;
use App\Filament\Resources\ComprobanteResource;
use App\Imports\ComprobanteLineaImport;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Support\Colors\Color;

class EditComprobante extends EditRecord
{
    protected static string $resource = ComprobanteResource::class;

    protected static string $view = 'custom.comprobante.edit-comprobante';

    protected function getHeaderActions(): array
    {
        return [

            Action::make('import_excel')
                ->label('Importar Lineas')
                ->color(Color::Blue)
                ->icon('heroicon-o-document-arrow-up')
                ->form([
                    FileUpload::make('file_import')
                        ->label('Archivo Excel')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    Excel::import(new ComprobanteLineaImport($this->getRecord()->id), $data['file_import']);

                    $this->fillForm();

                    Notification::make()
                        ->title('Se importo la información de manera correcta.')
                        ->icon('heroicon-m-check-circle')
                        ->body('Los datos importados correctamente')
                        ->success()
                        ->color('primary')
                        ->send();
                }),

            Action::make('export_excel')
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

            Action::make('export_pdf')
                ->label('Exportar PDF')
                ->color('primary')
                ->icon('heroicon-c-printer')
                ->action(function () {
                    $this->dispatch('print');
                })
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
                            ->inputMode('decimal')
                            ->live(onBlur: true)
                            ->prefix('$'),
                        TextInput::make('credito')
                            ->placeholder('Credito')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->inputMode('decimal')
                            ->live(onBlur: true)
                            ->prefix('$')
                    ])
                    ->reorderable()
                    ->cloneable()
                    ->grid(4)
                    ->collapsible()
                    ->defaultItems(1)
                    ->maxItems(10),
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
