<?php

namespace App\Filament\Resources\SuperSolidariaResource\Pages;

use App\Filament\Resources\SuperSolidariaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSuperSolidaria extends ViewRecord
{
    protected static string $resource = SuperSolidariaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
