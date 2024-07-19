<?php

namespace App\Filament\Resources\ProfesionResource\Pages;

use App\Filament\Resources\ProfesionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProfesions extends ManageRecords
{
    protected static string $resource = ProfesionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nueva Profesion'),
        ];
    }
}
