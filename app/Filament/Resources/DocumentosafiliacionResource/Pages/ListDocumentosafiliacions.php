<?php

namespace App\Filament\Resources\DocumentosafiliacionResource\Pages;

use App\Filament\Resources\DocumentosafiliacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentosafiliacions extends ListRecords
{
    protected static string $resource = DocumentosafiliacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Documento de Afiliacion'),
        ];
    }
}
