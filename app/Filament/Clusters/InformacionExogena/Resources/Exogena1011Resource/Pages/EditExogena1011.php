<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1011Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1011Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExogena1011 extends EditRecord
{
    protected static string $resource = Exogena1011Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
