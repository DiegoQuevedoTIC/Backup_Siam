<?php

namespace App\Filament\Resources\DesembolsoResource\Pages;

use App\Filament\Resources\DesembolsoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDesembolso extends ViewRecord
{
    protected static string $resource = DesembolsoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
