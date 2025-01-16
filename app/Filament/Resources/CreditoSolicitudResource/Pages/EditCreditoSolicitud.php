<?php

namespace App\Filament\Resources\CreditoSolicitudResource\Pages;

use App\Filament\Resources\CreditoSolicitudResource;
use App\Models\CuotaEncabezado;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class EditCreditoSolicitud extends EditRecord
{
    protected static string $resource = CreditoSolicitudResource::class;

    public $modoDesembolso;

    public function updatePlanDesembolso($modoDesembolso, $solicitud)
    {
        DB::table('plan_desembolsos')->where('solicitud_id', $solicitud)->update([
            'modo_desembolso' => $modoDesembolso,
        ]);

        // Notificación visual para el usuario
        Notification::make()
            ->title('Se actualizaron los datos correctamente')
            ->icon('heroicon-m-check-circle')
            ->body('Los datos fueron actualizados correctamente')
            ->success()
            ->send();
    }

    public function addComposicion($concepto, $nro_documento)
    {

        // Agregar el concepto y número de documento al plan de desembolso
        DB::table('cartera_composicion_conceptos')->insert([
            'tipo_documento' => 'PLI',
            'numero_documento' => $nro_documento,
            'concepto_descuento' => $concepto['concepto_descuento'],
            'prioridad' => $concepto['prioridad'],
            'valor_con_descuento' => $concepto['valor_con_descuento'],
            'porcentaje_descuento' => $concepto['porcentaje_descuento'],
            'valor' => 'S'
        ]);

        // Notificación visual para el usuario
        Notification::make()
            ->title('Se agregaron los datos correctamente')
            ->icon('heroicon-m-check-circle')
            ->body('Los datos fueron agregados correctamente')
            ->success()
            ->send();
    }

    public function removeItem($concepto, $nro_documento)
    {
        // Eliminar el concepto y número de documento del plan de desembolso
        DB::table('cartera_composicion_conceptos')->where([
            'tipo_documento' => 'PLI',
            'numero_documento' => $nro_documento,
            'concepto_descuento' => $concepto
        ])->delete();

        // Notificación visual para el usuario
        Notification::make()
            ->title('Se eliminó los datos correctamente')
            ->icon('heroicon-m-check-circle')
            ->body('Los datos fueron eliminados correctamente')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\ViewAction::make(),
                Actions\Action::make('preliquidacion')->label('Pre-liquidación')
                    ->modalContent(view('custom.credito_solicitudes.preliquidacion_table', ['solicitud' => $this->getRecord()->solicitud, 'tipo_documento' => 'PLI']))
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false),
                Actions\Action::make('garantias')->label('Garantias')
                    ->modalContent(view('custom.credito_solicitudes.garantias_table', ['solicitud' => $this->getRecord()->solicitud]))
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false),
                Actions\Action::make('aprobacion')->label('Aprobación')
                    ->form([
                        Section::make('Comite de aprobación')
                            ->columns(2)
                            ->schema([
                                Radio::make('estado')
                                    ->default($this->getRecord()->estado)
                                    ->options([
                                        'P' => 'Pendiente por aprobación',
                                        'C' => 'Cancelada',
                                        'M' => 'Monto desembolsado',
                                        'N' => 'Negado',
                                        'A' => 'Aprobado'
                                    ])
                                    ->descriptions([
                                        'P' => 'Pendiente por aprobación',
                                        'C' => 'Cancelada',
                                        'M' => 'Monto desembolsado',
                                        'N' => 'Negado',
                                        'A' => 'Aprobado'
                                    ]),
                                TextInput::make('ente_aprobador'),
                                TextInput::make('nro_acta'),
                                TextInput::make('observaciones'),
                            ]),
                    ])->action(function (array $data) {

                        $this->getRecord()->estado = $data['estado'];
                        $this->getRecord()->ente_aprobador = $data['ente_aprobador'];
                        $this->getRecord()->nro_acta_aprob = $data['nro_acta'];
                        $this->getRecord()->observaciones = $data['observaciones'];
                        $this->getRecord()->save();

                        Notification::make()
                            ->title('Se actualizo los datos correctamente')
                            ->icon('heroicon-m-check-circle')
                            ->body('Se actualizo los datos correctamente')
                            ->success()
                            ->send();
                    }),
                Actions\Action::make('plan_desembolso')->label('Plan Desembolso')
                    ->modalContent(view('custom.credito_solicitudes.plan_desembolso', ['solicitud' => $this->getRecord()->solicitud]))
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false),
            ])
        ];
    }
}
