<?php

namespace App\Filament\Resources\DocumentosotroResource\Pages;

use App\Filament\Resources\DocumentosotroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentosotro extends EditRecord
{
    protected static string $resource = DocumentosotroResource::class;

    protected function getHeaderActions(): array
    {
        return [
          
        ];
    }
}
