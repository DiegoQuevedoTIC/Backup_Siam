<?php

namespace App\Filament\Clusters\InformacionExogena\Resources\Exogena1010Resource\Pages;

use App\Filament\Clusters\InformacionExogena\Resources\Exogena1010Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExogena1010 extends EditRecord
{
    protected static string $resource = Exogena1010Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
