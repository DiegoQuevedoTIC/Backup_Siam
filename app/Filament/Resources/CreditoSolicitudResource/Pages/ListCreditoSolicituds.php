<?php

namespace App\Filament\Resources\CreditoSolicitudResource\Pages;

use App\Filament\Resources\CreditoSolicitudResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreditoSolicituds extends ListRecords
{
    protected static string $resource = CreditoSolicitudResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
