<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1012Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1012Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExogena1012s extends ListRecords
{
    protected static string $resource = Exogena1012Resource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
