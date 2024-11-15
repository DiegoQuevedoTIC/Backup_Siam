<?php

namespace App\Filament\Resources\InformacionExogenaResource\Pages;

use App\Filament\Resources\InformacionExogenaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInformacionExogena extends ViewRecord
{
    protected static string $resource = InformacionExogenaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
