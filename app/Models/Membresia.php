<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membresia extends Model
{
    protected $table = 'membresias';

    protected $fillable = [
        'socia_id',
        'plan_version_id',
        'fecha_inicio',
        'fecha_fin_programada',
        'fecha_cancelacion',
        'fecha_renovacion',
        'estatus',
        'metodo_pago',
        'dia_cobro',
        'ciclo_facturacion',
        'periodo_gracia_dias',
        'motivo_baja',
        'observaciones',
        'vendedor_user_id',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin_programada' => 'date',
        'fecha_cancelacion' => 'date',
        'fecha_renovacion' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function socia(): BelongsTo
    {
        return $this->belongsTo(Socia::class);
    }

    public function planVersion(): BelongsTo
    {
        return $this->belongsTo(PlanVersion::class);
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_user_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->whereRaw('LOWER(estatus) = ?', ['activa']);
    }

    public function scopeCanceladas($query)
    {
        return $query->whereRaw('LOWER(estatus) = ?', ['cancelada']);
    }

    public function scopeVencidas($query)
    {
        return $query->whereDate('fecha_fin_programada', '<', now()->toDateString());
    }

    public function scopePausadas($query)
    {
        return $query->whereRaw('LOWER(estatus) = ?', ['pausada']);
    }

    // Métodos de negocio
    public function estaActiva(): bool
    {
        return strtolower((string) $this->estatus) === 'activa'
            && now()->toDateString() >= $this->fecha_inicio->toDateString()
            && now()->toDateString() <= $this->fecha_fin_programada->toDateString();
    }

    public function estaVencida(): bool
    {
        return now()->toDateString() > $this->fecha_fin_programada->toDateString();
    }

    public function obtenDiasRestantes(): int
    {
        if (!$this->estaActiva()) {
            return 0;
        }
        return now()->diffInDays($this->fecha_fin_programada, false);
    }
}
