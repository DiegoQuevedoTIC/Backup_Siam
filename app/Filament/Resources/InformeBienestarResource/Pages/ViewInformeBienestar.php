<?php

namespace App\Filament\Resources\InformeBienestarResource\Pages;

use App\Filament\Resources\InformeBienestarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInformeBienestar extends ViewRecord
{
    protected static string $resource = InformeBienestarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
