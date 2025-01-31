<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1001Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1001Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExogena1001 extends EditRecord
{
    protected static string $resource = Exogena1001Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
