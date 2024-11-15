<?php

namespace App\Filament\Resources\CorreosMasivoResource\Pages;

use App\Filament\Resources\CorreosMasivoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorreosMasivo extends EditRecord
{
    protected static string $resource = CorreosMasivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
