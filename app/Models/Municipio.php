<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $fillable = [
        'estado_id',
        'nombre',
        'codigo',
    ];

    public function estado() {
        return $this->belongsTo(Estado::class);
    }

}