<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensajeRemitenteEliminado extends Model
{
    protected $table = 'mensaje_remitente_eliminados';

    public $timestamps = false;

    protected $fillable = [
        'mensaje_id',
        'eliminado_en',
    ];

    protected $casts = [
        'eliminado_en' => 'datetime',
    ];

    /**
     * Mensaje al que pertenece este registro.
     */
    public function mensaje(): BelongsTo
    {
        return $this->belongsTo(Mensaje::class, 'mensaje_id');
    }
}
