<?php

namespace App\Filament\Resources\CierreMensualResource\Pages;

use App\Filament\Resources\CierreMensualResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCierreMensual extends CreateRecord
{
    protected static string $resource = CierreMensualResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
