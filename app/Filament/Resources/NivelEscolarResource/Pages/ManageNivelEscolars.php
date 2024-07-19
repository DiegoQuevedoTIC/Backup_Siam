<?php

namespace App\Filament\Resources\NivelEscolarResource\Pages;

use App\Filament\Resources\NivelEscolarResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNivelEscolars extends ManageRecords
{
    protected static string $resource = NivelEscolarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Nivel Escolar'),
        ];
    }
}
