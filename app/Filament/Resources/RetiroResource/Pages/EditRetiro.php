<?php

namespace App\Filament\Resources\RetiroResource\Pages;

use App\Filament\Resources\RetiroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRetiro extends EditRecord
{
    protected static string $resource = RetiroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
