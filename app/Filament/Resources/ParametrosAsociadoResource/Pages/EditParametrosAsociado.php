<?php

namespace App\Filament\Resources\ParametrosAsociadoResource\Pages;

use App\Filament\Resources\ParametrosAsociadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParametrosAsociado extends EditRecord
{
    protected static string $resource = ParametrosAsociadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
