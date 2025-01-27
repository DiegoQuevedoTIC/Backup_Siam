<?php

namespace App\Filament\Resources\InformacionExogenaResource\Pages;

use App\Filament\Resources\InformacionExogenaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInformacionExogena extends EditRecord
{
    protected static string $resource = InformacionExogenaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
