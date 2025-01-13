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

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\ViewAction::make(),
                Actions\Action::make('preliquidacion')->label('Pre-liquidación')
                    ->modalContent(view('custom.preliquidacion.table', ['solicitud' => $this->getRecord()->solicitud]))
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false),
                Actions\Action::make('garantias')->label('Garantias'),
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
                Actions\Action::make('plan_desembolso')->label('Plan Desembolso'),
            ])
        ];
    }
}
