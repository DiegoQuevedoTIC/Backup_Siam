<?php

namespace App\Filament\Resources\CentralRiesgoResource\Pages;

use App\Filament\Resources\CentralRiesgoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCentralRiesgo extends CreateRecord
{
    protected static string $resource = CentralRiesgoResource::class;

    protected static ?string $pollingInterval = null;

    protected static string $view = 'custom.centrales.create-centrales';
}
