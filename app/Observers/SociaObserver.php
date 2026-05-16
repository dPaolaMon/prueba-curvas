<?php

namespace App\Observers;

use App\Models\Socia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SociaObserver
{
    /**
     * Handle the Socia "created" event.
     */
    public function created(Socia $socia): void
    {
        $socia->refresh();

        $username = (string) $socia->num_socia;
        $email = $socia->email ?: "socia_{$username}@curvasb.local";

        // Crear un usuario con el email de la socia
        $user = User::create([
            'name' => $socia->nombre . ' ' . $socia->apellidos,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make('cambiar'), // Contraseña fija
            'role' => 'socia',
        ]);

        // Asignar el user_id a la socia
        $socia->update(['user_id' => $user->id]);
    }

    /**
     * Handle the Socia "updated" event.
     */
    public function updated(Socia $socia): void
    {
        //
    }

    /**
     * Handle the Socia "deleted" event.
     */
    public function deleted(Socia $socia): void
    {
        // Eliminar el usuario asociado si existe
        if ($socia->user) {
            $socia->user->delete();
        }

        //Eliminar foto (si existe)
        if ($socia->foto) {
            Storage::disk('public')->delete($socia->foto);
        }
    }

    /**
     * Handle the Socia "restored" event.
     */
    public function restored(Socia $socia): void
    {
        //
    }

    /**
     * Handle the Socia "force deleted" event.
     */
    public function forceDeleted(Socia $socia): void
    {
        //
    }
}