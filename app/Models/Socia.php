<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socia extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'apellidos',
        'foto',
        'fecha_nacimiento',
        'ocupacion',
        'estado_civil',
        'celular',
        'email',
        'direccion',
        'colonia',
        'codigo_postal',
        'estado_id',
        'municipio_id',
        'estatus',
        'fecha_alta',
        'fecha_reingreso',
        'fecha_baja',
        'contacto_emergencia',
        'padecimiento_cronico',
        'factorx'
    ];

    protected $casts = [
        'num_socia' => 'integer',
        'fecha_nacimiento' => 'date',
        'fecha_alta' => 'date',
        'fecha_reingreso' => 'date',
        'fecha_baja' => 'date',
    ];

    public function estado() {
        return $this->belongsTo(Estado::class);
    }

    public function municipio() {
        return $this->belongsTo(Municipio::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function asistencias() {
        return $this->hasMany(Asistencia::class);
    }

    public function medidas() {
        return $this->hasMany(Medida::class);
    }

    public function membresias() {
        return $this->hasMany(Membresia::class);
    }

    public function pagos() {
        return $this->hasMany(Pago::class);
    }
}