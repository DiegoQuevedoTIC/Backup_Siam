<?php

namespace App\Filament\Resources\LibroOficialResource\Pages;

use App\Filament\Resources\LibroOficialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLibroOficial extends EditRecord
{
    protected static string $resource = LibroOficialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
