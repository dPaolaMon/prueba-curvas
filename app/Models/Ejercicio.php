<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ejercicio extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
    ];

    /**
     * Un ejercicio puede estar en muchos eventos
     */
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }
}
