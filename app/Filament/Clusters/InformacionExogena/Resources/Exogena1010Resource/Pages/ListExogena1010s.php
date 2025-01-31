<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1010Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1010Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExogena1010s extends ListRecords
{
    protected static string $resource = Exogena1010Resource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
