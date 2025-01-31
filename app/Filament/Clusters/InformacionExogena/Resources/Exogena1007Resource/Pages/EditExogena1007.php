<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1007Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1007Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExogena1007 extends EditRecord
{
    protected static string $resource = Exogena1007Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
