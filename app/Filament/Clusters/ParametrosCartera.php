<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class ParametrosCartera extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Gestión de Asociados';
    protected static ?string $navigationLabel = 'Solicitudes de Crédito';
    protected static ?int       $navigationSort = 2;
}
