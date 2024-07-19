<?php

namespace App\Filament\Resources\TipoDocumentoContableResource\Pages;

use App\Filament\Resources\TipoDocumentoContableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoDocumentoContables extends ListRecords
{
    protected static string $resource = TipoDocumentoContableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
