<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1009Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1009Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExogena1009 extends EditRecord
{
    protected static string $resource = Exogena1009Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
