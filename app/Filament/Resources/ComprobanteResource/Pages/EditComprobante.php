<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

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

class EditComprobante extends EditRecord
{
    protected static string $resource = ComprobanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
            Actions\CreateAction::make(),
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
            ->schema([
                //
                Toggle::make('usar_plantilla')
                    ->label('Usar plantilla')
                    ->live(),

                Select::make('plantilla')
                    ->label('Plantilla')
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
                    ->live(),

                Select::make('tipo_documento_contables_id')
                    ->label('Tipo de Documento')
                    ->native(false)
                    ->options($tipoDocumento)
                    ->required()
                    ->live(),

                TextInput::make('n_documento')
                    ->label('Nº de Documento')
                    ->required(),

                Select::make('tercero_id')
                    ->label('Tercero Comprobante')
                    ->required()
                    ->native(false)
                    ->relationship(name: 'tercero', titleAttribute: 'nombres'),

                DatePicker::make('fecha_comprobante')
                    ->label('Fecha de comprobante')
                    ->required()
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

                Toggle::make('is_plantilla')
                    ->label('¿Guardar como Plantilla?')
                    ->required(),

                Textarea::make('descripcion_comprobante')
                    ->label('Descripcion del Comprobante')
                    ->required(),

                TableRepeater::make('detalle')
                    ->label('Detalle comprobante')
                    ->relationship('comprobanteLinea')
                    ->schema([
                        Select::make('pucs_id')
                            ->label('Cuenta PUC')
                            ->options($puc)
                            ->live()
                            ->native(false)
                            ->searchable(),

                        Select::make('tercero_id')
                            ->label('Tercero Registro')
                            ->relationship(name: 'tercero', titleAttribute: 'nombres'),

                        TextInput::make('descripcion_linea')
                            ->label('Descripcion Linea'),

                        TextInput::make('debito')
                            ->placeholder('Debito')
                            ->mask(RawJs::make('$money($input)'))
                            ->numeric()
                            ->prefix('$')
                            ->disabled(function (Get $get): bool {
                                $query = Puc::all()->find($get('pucs_id'));
                                if (!is_null($query)) {
                                    $query = $query->toArray();
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
                                $query = Puc::all()->find($get('pucs_id'));
                                if (!is_null($query)) {
                                    $query = $query->toArray();
                                    if ($query['naturaleza'] != 'D') {
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
