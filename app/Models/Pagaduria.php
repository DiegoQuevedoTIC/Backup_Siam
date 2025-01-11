<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagaduria extends Model
{
    use HasFactory;

    protected $table = 'pagadurias';

    public function creditoSolicitudes()
    {
        return $this->hasMany(CreditoSolicitud::class, 'empresa');
    }
}
