<?php

namespace App\Filament\Resources\CentralRiesgoResource\Pages;

use App\Filament\Resources\CentralRiesgoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentralRiesgo extends EditRecord
{
    protected static string $resource = CentralRiesgoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
