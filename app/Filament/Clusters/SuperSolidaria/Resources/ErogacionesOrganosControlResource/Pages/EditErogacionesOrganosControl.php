<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\ErogacionesOrganosControlResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\ErogacionesOrganosControlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditErogacionesOrganosControl extends EditRecord
{
    protected static string $resource = ErogacionesOrganosControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
