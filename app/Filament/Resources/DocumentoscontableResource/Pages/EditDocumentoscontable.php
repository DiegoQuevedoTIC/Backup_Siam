<?php

namespace App\Filament\Resources\DocumentoscontableResource\Pages;

use App\Filament\Resources\DocumentoscontableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentoscontable extends EditRecord
{
    protected static string $resource = DocumentoscontableResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
