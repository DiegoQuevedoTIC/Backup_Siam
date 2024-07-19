<?php

namespace App\Filament\Resources\LibroOficialResource\Pages;

use App\Filament\Resources\LibroOficialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLibroOficials extends ListRecords
{
    protected static string $resource = LibroOficialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
