<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documentosotro extends Model
{
    use HasFactory;


            public function documentotipo(): BelongsTo
    {
        return $this->BelongsTo(Documentotipo::class);
    }

}
