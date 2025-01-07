<?php

namespace App\Filament\Resources\CreditoSolicitudResource\Pages;

use App\Filament\Resources\CreditoSolicitudResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreditoSolicitud extends EditRecord
{
    protected static string $resource = CreditoSolicitudResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
