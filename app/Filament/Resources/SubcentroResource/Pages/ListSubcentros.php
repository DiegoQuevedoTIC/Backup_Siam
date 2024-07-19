<?php

namespace App\Filament\Resources\SubcentroResource\Pages;

use App\Filament\Resources\SubcentroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubcentros extends ListRecords
{
    protected static string $resource = SubcentroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
