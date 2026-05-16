<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maquina extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Una máquina puede estar en muchas semanas
     */
    public function maquinasSemana(): HasMany
    {
        return $this->hasMany(MaquinaSemana::class);
    }
}

