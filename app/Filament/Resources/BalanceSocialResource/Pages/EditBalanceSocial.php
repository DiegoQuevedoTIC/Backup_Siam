<?php

namespace App\Filament\Resources\BalanceSocialResource\Pages;

use App\Filament\Resources\BalanceSocialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBalanceSocial extends EditRecord
{
    protected static string $resource = BalanceSocialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
