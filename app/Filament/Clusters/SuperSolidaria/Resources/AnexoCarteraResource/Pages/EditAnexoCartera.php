<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\AnexoCarteraResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\AnexoCarteraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnexoCartera extends EditRecord
{
    protected static string $resource = AnexoCarteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
