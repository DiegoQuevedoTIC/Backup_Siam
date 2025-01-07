<?php

namespace App\Filament\Resources\CreditoSolicitudResource\Pages;

use App\Filament\Resources\CreditoSolicitudResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCreditoSolicitud extends ViewRecord
{
    protected static string $resource = CreditoSolicitudResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
