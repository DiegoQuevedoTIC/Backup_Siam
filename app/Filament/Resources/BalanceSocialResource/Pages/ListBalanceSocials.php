<?php

namespace App\Filament\Resources\BalanceSocialResource\Pages;

use App\Filament\Resources\BalanceSocialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBalanceSocials extends ListRecords
{
    protected static string $resource = BalanceSocialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
