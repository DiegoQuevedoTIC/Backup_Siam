<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class InformeSaldos extends Cluster
{
    protected static ?string    $navigationIcon = 'heroicon-o-cog';
    protected static ?string    $navigationGroup = 'Gestión de Asociados';
    protected static ?int       $navigationSort = 4;
    protected static?string     $navigationLabel = 'Cartera de Credito';
    protected static?string     $modelLabel = 'Cartera de Credito';
}
