<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarantiaCartera extends Model
{
    //
    protected $table = 'garantias_cartera';

    protected $guarded = [];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }
}
