<?php

namespace App\Filament\Resources\AuxiliarATerceroResource\Pages;

use App\Filament\Resources\AuxiliarATerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuxiliarATercero extends EditRecord
{
    protected static string $resource = AuxiliarATerceroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
