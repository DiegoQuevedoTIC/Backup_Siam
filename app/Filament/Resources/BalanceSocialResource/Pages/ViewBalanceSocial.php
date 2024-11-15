<?php

namespace App\Filament\Resources\BalanceSocialResource\Pages;

use App\Filament\Resources\BalanceSocialResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBalanceSocial extends ViewRecord
{
    protected static string $resource = BalanceSocialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
