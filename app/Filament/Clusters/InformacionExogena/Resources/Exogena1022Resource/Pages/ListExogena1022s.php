<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1022Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1022Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExogena1022s extends ListRecords
{
    protected static string $resource = Exogena1022Resource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
