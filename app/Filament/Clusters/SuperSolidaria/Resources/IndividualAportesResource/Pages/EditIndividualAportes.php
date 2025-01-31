<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\IndividualAportesResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\IndividualAportesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndividualAportes extends EditRecord
{
    protected static string $resource = IndividualAportesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
