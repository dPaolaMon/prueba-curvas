<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = ['socia_id', 'fecha', 'hora'];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i:s',
    ];

    public function socia()
    {
        return $this->belongsTo(Socia::class);
    }
}
