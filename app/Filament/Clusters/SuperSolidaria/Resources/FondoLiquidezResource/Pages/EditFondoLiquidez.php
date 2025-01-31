<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\FondoLiquidezResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\FondoLiquidezResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFondoLiquidez extends EditRecord
{
    protected static string $resource = FondoLiquidezResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
