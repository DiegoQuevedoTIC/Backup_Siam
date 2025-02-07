<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class InformeSaldosAportes extends Cluster
{
    protected static ?string    $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string    $navigationGroup = 'Gestión de Asociados';
    protected static ?int       $navigationSort = 5;
    protected static?string     $navigationLabel = 'Aportes y Ahorros ';
    protected static?string     $modelLabel = 'Aportes y Ahorros';
}
