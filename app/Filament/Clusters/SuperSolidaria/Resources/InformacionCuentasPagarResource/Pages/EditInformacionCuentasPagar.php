<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\InformacionCuentasPagarResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\InformacionCuentasPagarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInformacionCuentasPagar extends EditRecord
{
    protected static string $resource = InformacionCuentasPagarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
