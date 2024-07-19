<?php

namespace App\Filament\Resources\PucResource\Pages;

use App\Filament\Resources\PucResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPucs extends ListRecords
{
    protected static string $resource = PucResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
