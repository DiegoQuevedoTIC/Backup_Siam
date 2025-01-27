<?php

namespace App\Filament\Resources\SuperSolidariaResource\Pages;

use App\Filament\Resources\SuperSolidariaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuperSolidaria extends CreateRecord
{
    protected static string $resource = SuperSolidariaResource::class;

    protected static ?string $pollingInterval = null;

    protected static string $view = 'custom.supersolidaria.create-supersolidaria';
}
