<?php

namespace App\Filament\Resources\DocumentoscontableResource\Pages;

use App\Filament\Resources\DocumentoscontableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoscontables extends ListRecords
{
    protected static string $resource = DocumentoscontableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make() ->label('+ Nuevo Documento Contable'),
        ];
    }
}
