<?php

namespace App\Filament\Resources\CierreAnualResource\Pages;

use App\Filament\Resources\CierreAnualResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCierreAnual extends EditRecord
{
    protected static string $resource = CierreAnualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
