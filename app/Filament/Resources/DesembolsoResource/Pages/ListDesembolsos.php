<?php

namespace App\Filament\Resources\DesembolsoResource\Pages;

use App\Filament\Resources\DesembolsoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDesembolsos extends ListRecords
{
    protected static string $resource = DesembolsoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
