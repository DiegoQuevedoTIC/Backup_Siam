<?php

namespace App\Filament\Resources\PagaduriaResource\Pages;

use App\Filament\Resources\PagaduriaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePagadurias extends ManageRecords
{
    protected static string $resource = PagaduriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nueva Pagaduria'),
        ];
    }
}
