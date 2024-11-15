<?php

namespace App\Filament\Resources\RetiroResource\Pages;

use App\Filament\Resources\RetiroResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRetiro extends ViewRecord
{
    protected static string $resource = RetiroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
