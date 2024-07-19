<?php

namespace App\Filament\Resources\CalificacionClienteResource\Pages;

use App\Filament\Resources\CalificacionClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCalificacionClientes extends ManageRecords
{
    protected static string $resource = CalificacionClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nueva Calificacion de Cliente'),
        ];
    }
}
