<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class InformeSaldosCdat extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';
    protected static ?string    $navigationGroup = 'Gestión de Asociados';
    protected static ?int       $navigationSort = 5;
    protected static?string     $navigationLabel = 'Cdat - Certificados Depositos ';
    protected static?string     $modelLabel = 'Cdat - Certificados Depositos';
}
