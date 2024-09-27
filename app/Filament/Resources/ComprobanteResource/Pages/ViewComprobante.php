<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

use App\Filament\Resources\ComprobanteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;

class ViewComprobante extends ViewRecord
{
    protected static string $resource = ComprobanteResource::class;

    protected static string $view = 'custom.comprobante.view-comprobante';
}
