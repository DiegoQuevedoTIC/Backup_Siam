<?php

namespace App\Filament\Resources\BalanceResource\Pages;

use App\Filament\Resources\BalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBalance extends CreateRecord
{
    protected static string $resource = BalanceResource::class;

    protected static ?string $pollingInterval = null;

    protected static string $view = 'custom.balance.create-balance';
}
