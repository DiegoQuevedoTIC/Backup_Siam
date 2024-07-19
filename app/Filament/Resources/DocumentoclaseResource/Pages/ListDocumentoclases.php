<?php

namespace App\Filament\Resources\DocumentoclaseResource\Pages;

use App\Filament\Resources\DocumentoclaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoclases extends ListRecords
{
    protected static string $resource = DocumentoclaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nueva Clase de Documento'),
        ];
    }
}
