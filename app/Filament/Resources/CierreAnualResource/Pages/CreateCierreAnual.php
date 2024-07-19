<?php

namespace App\Filament\Resources\CierreAnualResource\Pages;

use App\Filament\Resources\CierreAnualResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCierreAnual extends CreateRecord
{
    protected static string $resource = CierreAnualResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
