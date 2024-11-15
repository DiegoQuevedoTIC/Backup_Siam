<?php

namespace App\Filament\Resources\SuperSolidariaResource\Pages;

use App\Filament\Resources\SuperSolidariaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuperSolidaria extends EditRecord
{
    protected static string $resource = SuperSolidariaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
