<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medida extends Model
{
    protected $fillable = [
        'socia_id',
        'fecha_registro',
        'busto',
        'cintura',
        'abdomen',
        'caderas',
        'muslo',
        'brazo',
        'peso',
        'altura',
        'porcentaje_grasa',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    /**
     * Una medida pertenece a una socia.
     */
    public function socia(): BelongsTo
    {
        return $this->belongsTo(Socia::class);
    }
}
