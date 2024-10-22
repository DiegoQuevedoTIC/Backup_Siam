<?php

namespace App\Filament\Resources\SaldoConciliadoResource\Pages;

use App\Filament\Resources\SaldoConciliadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaldoConciliado extends EditRecord
{
    protected static string $resource = SaldoConciliadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
