<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\CreditoBancosOtrosResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\CreditoBancosOtrosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreditoBancosOtros extends ListRecords
{
    protected static string $resource = CreditoBancosOtrosResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
