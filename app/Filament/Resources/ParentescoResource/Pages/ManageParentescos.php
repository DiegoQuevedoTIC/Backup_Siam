<?php

namespace App\Filament\Resources\ParentescoResource\Pages;

use App\Filament\Resources\ParentescoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageParentescos extends ManageRecords
{
    protected static string $resource = ParentescoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Parentesco'),
        ];
    }
}
