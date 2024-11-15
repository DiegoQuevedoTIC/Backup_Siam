<?php

namespace App\Filament\Resources\CorreosMasivoResource\Pages;

use App\Filament\Resources\CorreosMasivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCorreosMasivo extends ViewRecord
{
    protected static string $resource = CorreosMasivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
