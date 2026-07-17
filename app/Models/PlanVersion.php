<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanVersion extends Model
{
    protected $table = 'planes_versiones';

    protected $fillable = [
        'plan_id',
        'nombre_comercial',
        'vigencia_desde',
        'vigencia_hasta',
        'precio_inscripcion',
        'precio_mensualidad',
        'precio_mensualidad_recurrente',
        'precio_pago_unico',
        'meses_duracion',
        'meses_cobrables',
        'meses_gratis',
        'comision_monto',
        'retencion_monto',
        'retencion_mes_numero',
        'notas',
        'creado_por',
        'actualizado_por',
        'estado_publicacion',
    ];

    protected $casts = [
        'vigencia_desde' => 'date',
        'vigencia_hasta' => 'date',
        'precio_inscripcion' => 'decimal:2',
        'precio_mensualidad' => 'decimal:2',
        'precio_mensualidad_recurrente' => 'decimal:2',
        'precio_pago_unico' => 'decimal:2',
        'comision_monto' => 'decimal:2',
        'retencion_monto' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function actualizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    public function membresias(): HasMany
    {
        return $this->hasMany(Membresia::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    // Scopes
    public function scopePublicadas($query)
    {
        return $query->where('estado_publicacion', 'publicado');
    }

    public function scopeBorradores($query)
    {
        return $query->where('estado_publicacion', 'borrador');
    }

    public function scopeVigentes($query)
    {
        return $query->where('vigencia_desde', '<=', now()->toDateString())
                     ->where(function ($q) {
                         $q->whereNull('vigencia_hasta')
                           ->orWhere('vigencia_hasta', '>=', now()->toDateString());
                     });
    }

    // Métodos de negocio
    public function puedeBorrarse(): bool
    {
        return $this->membresias()->count() === 0 && $this->pagos()->count() === 0;
    }

    public function tienePagos(): bool
    {
        return $this->pagos()->count() > 0;
    }

    public function puedeEditarseMontos(): bool
    {
        return !$this->tienePagos();
    }

    public function esVigente(): bool
    {
        $hoy = now()->toDateString();
        return $this->vigencia_desde <= $hoy
            && (is_null($this->vigencia_hasta) || $this->vigencia_hasta >= $hoy)
            && $this->estado_publicacion === 'publicado';
    }
}
