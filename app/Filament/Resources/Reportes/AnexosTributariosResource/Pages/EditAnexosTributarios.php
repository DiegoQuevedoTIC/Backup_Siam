<?php

namespace App\Filament\Resources\Reportes\AnexosTributariosResource\Pages;

use App\Filament\Resources\Reportes\AnexosTributariosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnexosTributarios extends EditRecord
{
    protected static string $resource = AnexosTributariosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
