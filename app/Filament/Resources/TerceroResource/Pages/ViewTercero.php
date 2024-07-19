<?php

namespace App\Filament\Resources\TerceroResource\Pages;

use App\Filament\Resources\TerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTercero extends ViewRecord
{
    protected static string $resource = TerceroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
