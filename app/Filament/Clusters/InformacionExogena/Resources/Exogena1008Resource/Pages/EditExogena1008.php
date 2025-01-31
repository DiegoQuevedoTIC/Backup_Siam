<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExogena1008 extends EditRecord
{
    protected static string $resource = Exogena1008Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
