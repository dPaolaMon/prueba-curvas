<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $fillable = [
        'pais_id',
        'nombre',
        'codigo',
    ];
}