<?php

namespace App\Filament\Resources\FirmaResource\Pages;

use App\Filament\Resources\FirmaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFirmas extends ManageRecords
{
    protected static string $resource = FirmaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
