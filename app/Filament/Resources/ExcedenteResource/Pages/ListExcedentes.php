<?php

namespace App\Filament\Resources\ExcedenteResource\Pages;

use App\Filament\Resources\ExcedenteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExcedentes extends ListRecords
{
    protected static string $resource = ExcedenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
