<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\InformeOrganosDirecciónControlResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\InformeOrganosDirecciónControlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInformeOrganosDirecciónControl extends EditRecord
{
    protected static string $resource = InformeOrganosDirecciónControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
