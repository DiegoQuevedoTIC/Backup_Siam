<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TableroLogo extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    /**
     * @var view-string
     */
    protected static string $view = 'custom.widgets.tablero';

}
