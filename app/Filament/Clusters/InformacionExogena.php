<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class InformacionExogena extends Cluster
{

    protected static ?string    $navigationGroup = 'Informes de Cumplimiento';
    protected static ?string    $navigationIcon = 'heroicon-o-document-text';
    protected static?string     $navigationLabel = 'Información Exógena';
    protected static?string     $modelLabel = 'Información Exógena';
    protected static ?int       $navigationSort = 99;
}



