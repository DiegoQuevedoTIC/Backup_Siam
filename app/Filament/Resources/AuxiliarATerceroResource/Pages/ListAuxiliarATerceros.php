<?php

namespace App\Filament\Resources\AuxiliarATerceroResource\Pages;

use App\Filament\Resources\AuxiliarATerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuxiliarATerceros extends ListRecords
{
    protected static string $resource = AuxiliarATerceroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
