<?php

namespace App\Filament\Resources\ConsultaComprobanteResource\Pages;

use App\Filament\Resources\ConsultaComprobanteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateConsultaComprobante extends CreateRecord
{
    protected static string $resource = ConsultaComprobanteResource::class;

    protected static string $view = 'custom.consultas.consulta-comprobante';
}
