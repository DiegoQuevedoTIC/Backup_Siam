<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1022Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1022Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExogena1022 extends EditRecord
{
    protected static string $resource = Exogena1022Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
