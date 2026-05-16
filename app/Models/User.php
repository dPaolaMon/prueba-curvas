<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'suspendido',
        'theme',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'suspendido' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Mensajes enviados por este usuario.
     */
    public function mensajesEnviados(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'remitente_id');
    }

    /**
     * Registros de destinatario de este usuario (bandeja de entrada).
     */
    public function mensajesRecibidos(): HasMany
    {
        return $this->hasMany(MensajeDestinatario::class, 'destinatario_id');
    }

    /**
     * Mensajes no leídos del usuario (útil para el badge del navbar).
     */
    public function mensajesNoLeidos(): HasMany
    {
        return $this->hasMany(MensajeDestinatario::class, 'destinatario_id')
                    ->whereNull('leido_en')
                    ->whereNull('eliminado_en');
    }
}
