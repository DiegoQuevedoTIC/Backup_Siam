<?php

namespace App\Filament\Resources\PagoColectivoResource\Pages;

use App\Filament\Resources\PagoColectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPagoColectivo extends EditRecord
{
    protected static string $resource = PagoColectivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
