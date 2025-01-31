<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1001Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1001Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExogena1001 extends ListRecords
{
    protected static string $resource = Exogena1001Resource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
