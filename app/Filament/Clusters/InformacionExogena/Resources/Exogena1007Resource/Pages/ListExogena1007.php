<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1007Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1007Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExogena1007 extends ListRecords
{
    protected static string $resource = Exogena1007Resource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
