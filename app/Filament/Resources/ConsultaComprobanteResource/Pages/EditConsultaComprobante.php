<?php

namespace App\Filament\Resources\ConsultaComprobanteResource\Pages;

use App\Filament\Resources\ConsultaComprobanteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsultaComprobante extends EditRecord
{
    protected static string $resource = ConsultaComprobanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
