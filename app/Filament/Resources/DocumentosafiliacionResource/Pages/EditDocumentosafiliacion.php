<?php

namespace App\Filament\Resources\DocumentosafiliacionResource\Pages;

use App\Filament\Resources\DocumentosafiliacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentosafiliacion extends EditRecord
{
    protected static string $resource = DocumentosafiliacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
