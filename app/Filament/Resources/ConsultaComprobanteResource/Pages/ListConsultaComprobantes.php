<?php

namespace App\Filament\Resources\ConsultaComprobanteResource\Pages;

use App\Filament\Resources\ConsultaComprobanteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultaComprobantes extends ListRecords
{
    protected static string $resource = ConsultaComprobanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Generar Revision'),
        ];
    }
}
