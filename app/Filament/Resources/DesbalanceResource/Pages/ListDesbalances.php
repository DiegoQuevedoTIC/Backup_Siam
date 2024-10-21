<?php

namespace App\Filament\Resources\DesbalanceResource\Pages;

use App\Filament\Resources\DesbalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDesbalances extends ListRecords
{
    protected static string $resource = DesbalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Generar Revision'),
        ];
    }
}
