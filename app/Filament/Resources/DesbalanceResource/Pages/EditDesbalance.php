<?php

namespace App\Filament\Resources\DesbalanceResource\Pages;

use App\Filament\Resources\DesbalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesbalance extends EditRecord
{
    protected static string $resource = DesbalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
