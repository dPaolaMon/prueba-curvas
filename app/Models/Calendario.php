<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calendario extends Model
{
    protected $fillable = [
        'dia',
        'ejercicio',
        'color',
        'es_nota',
    ];

    protected $casts = [
        'dia' => 'date',
        'es_nota' => 'boolean',
    ];
}
