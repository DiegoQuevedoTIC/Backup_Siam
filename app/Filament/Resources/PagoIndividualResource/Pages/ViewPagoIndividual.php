<?php

namespace App\Filament\Resources\PagoIndividualResource\Pages;

use App\Filament\Resources\PagoIndividualResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPagoIndividual extends ViewRecord
{
    protected static string $resource = PagoIndividualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
