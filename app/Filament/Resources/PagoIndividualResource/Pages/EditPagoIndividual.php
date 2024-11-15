<?php

namespace App\Filament\Resources\PagoIndividualResource\Pages;

use App\Filament\Resources\PagoIndividualResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPagoIndividual extends EditRecord
{
    protected static string $resource = PagoIndividualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
