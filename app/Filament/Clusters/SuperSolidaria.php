<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class SuperSolidaria extends Cluster
{
    protected static ?string    $navigationGroup = 'Informes de Cumplimiento';
    protected static?string     $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    protected static?string     $navigationLabel = 'Informes  SuperSolidaria';
    protected static?string     $modelLabel = 'Informes SuperSolidaria';
    protected static ?int       $navigationSort = 100;
}
