<?php

namespace App\Filament\Resources\ReaperturaCierreResource\Pages;

use App\Filament\Resources\ReaperturaCierreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReaperturaCierre extends EditRecord
{
    protected static string $resource = ReaperturaCierreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
