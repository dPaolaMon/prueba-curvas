<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class KioskoMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Verificar que el usuario esté autenticado
        if (!$user) {
            return redirect()->route('login');
        }

        // Verificar que tenga rol de admin, gerente o entrenadora
        $rolesPermitidos = ['ADMINISTRADOR', 'GERENTE', 'ENTRENADORA'];
        if (!in_array(strtoupper($user->role), $rolesPermitidos)) {
            abort(403, 'No tienes permisos para acceder al modo kiosko.');
        }

        // Calcular minutos hasta medianoche
        $ahora = Carbon::now();
        $medianoche = $ahora->copy()->addDay()->startOfDay();
        $minutosHastaMedianoche = $ahora->diffInMinutes($medianoche);

        // Establecer el lifetime de la sesión hasta medianoche
        config(['session.lifetime' => max($minutosHastaMedianoche, 1)]);

        return $next($request);
    }
}
