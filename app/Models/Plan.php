<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estatus',
        'creado_por',
        'actualizado_por',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function versiones(): HasMany
    {
        return $this->hasMany(PlanVersion::class);
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function actualizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estatus', 'activo');
    }

    public function scopeInactivos($query)
    {
        return $query->where('estatus', 'inactivo');
    }

    // Métodos de negocio
    public function puedeBorrarse(): bool
    {
        return $this->versiones()->count() === 0;
    }

    public function obtenerVersionVigente()
    {
        return $this->versiones()
            ->where('estado_publicacion', 'publicado')
            ->where('vigencia_desde', '<=', now()->toDateString())
            ->where(function ($query) {
                $query->whereNull('vigencia_hasta')
                      ->orWhere('vigencia_hasta', '>=', now()->toDateString());
            })
            ->first();
    }
}
