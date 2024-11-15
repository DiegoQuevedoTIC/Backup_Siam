<?php

namespace App\Filament\Resources\ReversoResource\Pages;

use App\Filament\Resources\ReversoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReversos extends ListRecords
{
    protected static string $resource = ReversoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
