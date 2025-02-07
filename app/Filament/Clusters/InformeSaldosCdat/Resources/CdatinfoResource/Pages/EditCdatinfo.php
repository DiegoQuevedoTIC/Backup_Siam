<?php

namespace App\Filament\Clusters\InformeSaldosCdat\Resources\CdatinfoResource\Pages;

use App\Filament\Clusters\InformeSaldosCdat\Resources\CdatinfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCdatinfo extends EditRecord
{
    protected static string $resource = CdatinfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
