<?php

namespace App\Filament\Resources\EstadoClienteResource\Pages;

use App\Filament\Resources\EstadoClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEstadoClientes extends ManageRecords
{
    protected static string $resource = EstadoClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Estado Vinculacion'),
        ];
    }
}
