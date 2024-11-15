<?php

namespace App\Filament\Resources\ReversoResource\Pages;

use App\Filament\Resources\ReversoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReverso extends EditRecord
{
    protected static string $resource = ReversoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
