<?php

namespace App\Filament\Resources\ControlCumpleamosResource\Pages;

use App\Filament\Resources\ControlCumpleamosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditControlCumpleamos extends EditRecord
{
    protected static string $resource = ControlCumpleamosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
