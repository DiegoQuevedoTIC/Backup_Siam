<?php

namespace App\Filament\Resources\DocumentosotroResource\Pages;

use App\Filament\Resources\DocumentosotroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentosotros extends ListRecords
{
    protected static string $resource = DocumentosotroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Documento'),
        ];
    }
}
