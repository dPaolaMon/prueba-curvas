<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KioskoSession extends Model
{
    protected $fillable = ['user_id', 'token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public function isExpired()
    {
        return now()->isAfter($this->expires_at);
    }
}
