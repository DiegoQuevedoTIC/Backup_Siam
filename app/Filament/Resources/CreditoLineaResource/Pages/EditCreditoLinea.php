<?php

namespace App\Filament\Resources\CreditoLineaResource\Pages;

use App\Filament\Resources\CreditoLineaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreditoLinea extends EditRecord
{
    protected static string $resource = CreditoLineaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
