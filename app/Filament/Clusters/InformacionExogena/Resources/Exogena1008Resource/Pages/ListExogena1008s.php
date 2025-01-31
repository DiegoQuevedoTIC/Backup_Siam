<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExogena1008s extends ListRecords
{
    protected static string $resource = Exogena1008Resource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
