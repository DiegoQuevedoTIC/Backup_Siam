<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\IndividualCarteraCreditoResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\IndividualCarteraCreditoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndividualCarteraCredito extends EditRecord
{
    protected static string $resource = IndividualCarteraCreditoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
