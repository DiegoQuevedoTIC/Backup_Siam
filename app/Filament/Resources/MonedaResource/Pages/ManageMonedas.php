<?php

namespace App\Filament\Resources\MonedaResource\Pages;

use App\Filament\Resources\MonedaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMonedas extends ManageRecords
{
    protected static string $resource = MonedaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nueva Moneda'),
        ];
    }
}
