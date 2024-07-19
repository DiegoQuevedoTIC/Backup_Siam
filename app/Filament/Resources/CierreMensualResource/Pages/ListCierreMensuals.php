<?php

namespace App\Filament\Resources\CierreMensualResource\Pages;

use App\Filament\Resources\CierreMensualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCierreMensuals extends ListRecords
{
    protected static string $resource = CierreMensualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
