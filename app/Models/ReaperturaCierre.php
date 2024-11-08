<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReaperturaCierre extends Model
{
    use HasFactory;

    protected $table = 'reapertura_cierres';

    protected $fillable = ['amo', 'mes'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
