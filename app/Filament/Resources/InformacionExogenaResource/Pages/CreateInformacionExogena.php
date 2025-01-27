<?php

namespace App\Filament\Resources\InformacionExogenaResource\Pages;

use App\Filament\Resources\InformacionExogenaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInformacionExogena extends CreateRecord
{
    protected static string $resource = InformacionExogenaResource::class;

    protected static ?string $pollingInterval = null;

    protected static string $view = 'custom.exogena.create-exogena';
}
