<?php

namespace App\Filament\Resources\DesembolsoResource\Pages;

use App\Filament\Resources\DesembolsoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesembolso extends EditRecord
{
    protected static string $resource = DesembolsoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
