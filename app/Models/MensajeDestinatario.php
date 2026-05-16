<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensajeDestinatario extends Model
{
    protected $table = 'mensaje_destinatarios';

    protected $fillable = [
        'mensaje_id',
        'destinatario_id',
        'leido_en',
        'eliminado_en',
    ];

    protected $casts = [
        'leido_en'     => 'datetime',
        'eliminado_en' => 'datetime',
    ];

    /**
     * Mensaje al que pertenece este registro.
     */
    public function mensaje(): BelongsTo
    {
        return $this->belongsTo(Mensaje::class, 'mensaje_id');
    }

    /**
     * Usuario destinatario.
     */
    public function destinatario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destinatario_id');
    }

    /**
     * Indica si el mensaje ya fue leído.
     */
    public function getEsLeidoAttribute(): bool
    {
        return $this->leido_en !== null;
    }

    /**
     * Indica si el destinatario lo eliminó de su bandeja.
     */
    public function getEsEliminadoAttribute(): bool
    {
        return $this->eliminado_en !== null;
    }

    /**
     * Marca el mensaje como leído en este momento (si aún no lo está).
     */
    public function marcarComoLeido(): void
    {
        if ($this->leido_en === null) {
            $this->update(['leido_en' => now()]);
        }
    }
}
