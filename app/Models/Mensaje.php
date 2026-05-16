<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mensaje extends Model
{
    protected $table = 'mensajes';

    protected $fillable = [
        'remitente_id',
        'asunto',
        'cuerpo',
    ];

    /**
     * Usuario que envió el mensaje.
     */
    public function remitente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'remitente_id');
    }

    /**
     * Todos los destinatarios del mensaje.
     */
    public function destinatarios(): HasMany
    {
        return $this->hasMany(MensajeDestinatario::class, 'mensaje_id');
    }

    /**
     * Registro de eliminación por parte del remitente (si existe).
     */
    public function remitenteEliminado(): HasOne
    {
        return $this->hasOne(MensajeRemitenteEliminado::class, 'mensaje_id');
    }

    /**
     * Indica si el remitente eliminó este mensaje de su bandeja de enviados.
     */
    public function getEliminadoPorRemitenteAttribute(): bool
    {
        return $this->remitenteEliminado !== null;
    }
}
