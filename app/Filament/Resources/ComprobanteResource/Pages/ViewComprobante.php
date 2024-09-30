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

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('fecha_comprobante'),
                Infolists\Components\TextEntry::make('tipoDocumentoContable.tipo_documento'),
                Infolists\Components\TextEntry::make('n_documento'),
                Infolists\Components\TextEntry::make('tercero.tercero_id'),
                Infolists\Components\TextEntry::make('descripcion_comprobante'),
            ]);
    }
}
