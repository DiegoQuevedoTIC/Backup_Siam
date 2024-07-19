<?php

namespace App\Filament\Resources\BalanceResource\Pages;

use App\Filament\Resources\BalanceResource;
use Filament\Resources\Pages\Page;

class ViewBalance extends Page
{
    protected static string $resource = BalanceResource::class;

    protected static string $view = 'filament.resources.balance-resource.pages.view-balance';
}
