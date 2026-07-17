<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'membresia_id',
        'socia_id',
        'plan_version_id',
        'folio_pago',
        'tipo_pago',
        'periodo_anio',
        'periodo_mes',
        'fecha_programada',
        'fecha_pago',
        'moneda',
        'monto_lista',
        'monto_descuento',
        'monto_recargo',
        'monto_final',
        'metodo_pago',
        'referencia_externa',
        'estatus',
        'snapshot_json',
        'comision_monto',
        'comision_pagable_en',
        'retencion_monto',
        'retencion_aplica',
        'retencion_liberable_en',
        'registrado_por',
        'anulado_por',
        'anulado_at',
        'motivo_anulacion',
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_pago' => 'datetime',
        'comision_pagable_en' => 'date',
        'retencion_liberable_en' => 'date',
        'anulado_at' => 'datetime',
        'snapshot_json' => 'array',
        'monto_lista' => 'decimal:2',
        'monto_descuento' => 'decimal:2',
        'monto_recargo' => 'decimal:2',
        'monto_final' => 'decimal:2',
        'comision_monto' => 'decimal:2',
        'retencion_monto' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function membresia(): BelongsTo
    {
        return $this->belongsTo(Membresia::class);
    }

    public function socia(): BelongsTo
    {
        return $this->belongsTo(Socia::class);
    }

    public function planVersion(): BelongsTo
    {
        return $this->belongsTo(PlanVersion::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function anuladoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'anulado_por');
    }

    // Scopes
    public function scopeAplicados($query)
    {
        return $query->where('estatus', 'aplicado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estatus', 'pendiente');
    }

    public function scopeAnulados($query)
    {
        return $query->where('estatus', 'anulado');
    }

    public function scopeReembolsados($query)
    {
        return $query->where('estatus', 'reembolsado');
    }

    public function scopePorMes($query, int $anio, int $mes)
    {
        return $query->where('periodo_anio', $anio)
                     ->where('periodo_mes', $mes);
    }

    // Métodos de negocio
    public function esAnulable(): bool
    {
        return in_array($this->estatus, ['aplicado', 'pendiente']);
    }

    public function estaAnulado(): bool
    {
        return $this->estatus === 'anulado' && !is_null($this->anulado_at);
    }

    public function generarFolio(): string
    {
        // Formato: PAG-2026-07-09-001 (PAG-AAAA-MM-DD-NUMERO)
        $fecha = now()->format('Y-m-d');
        $contador = self::where('folio_pago', 'like', "PAG-{$fecha}-%")
                        ->count() + 1;
        
        return sprintf('PAG-%s-%03d', $fecha, $contador);
    }

    public function obtenerSnapshot(): array
    {
        return $this->snapshot_json ?? [];
    }

    public function generarSnapshot(): void
    {
        $this->snapshot_json = [
            'plan_version_id' => $this->plan_version_id,
            'plan_nombre' => $this->planVersion->nombre_comercial,
            'precio_inscripcion' => $this->planVersion->precio_inscripcion,
            'precio_mensualidad' => $this->planVersion->precio_mensualidad,
            'comision_monto' => $this->planVersion->comision_monto,
            'retencion_monto' => $this->planVersion->retencion_monto,
            'retencion_mes_numero' => $this->planVersion->retencion_mes_numero,
            'generado_en' => now()->toIso8601String(),
        ];
    }

    public function puedeTenerRetencion(): bool
    {
        return $this->retencion_monto > 0 && $this->retencion_aplica === true;
    }

    public function calcularMontoFinal(): void
    {
        $this->monto_final = $this->monto_lista - $this->monto_descuento + $this->monto_recargo;
    }
}
