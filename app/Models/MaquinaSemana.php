<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaquinaSemana extends Model
{
    protected $table = 'maquinas_semana';

    protected $fillable = [
        'maquina_id',
        'num_semana',
        'mes',
        'anio',
    ];

    /**
     * Pertenece a una máquina
     */
    public function maquina(): BelongsTo
    {
        return $this->belongsTo(Maquina::class);
    }

    /**
     * Una máquina de semana puede tener muchos eventos
     */
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }
}
