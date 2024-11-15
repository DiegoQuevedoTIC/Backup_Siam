<?php

namespace App\Filament\Resources\InformeBienestarResource\Pages;

use App\Filament\Resources\InformeBienestarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInformeBienestar extends EditRecord
{
    protected static string $resource = InformeBienestarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
