<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMensajeRequest;
use App\Models\Mensaje;
use App\Models\MensajeDestinatario;
use App\Models\MensajeRemitenteEliminado;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MensajeController extends Controller
{
    /**
     * Bandeja de entrada del usuario autenticado.
     */
    public function index(Request $request): View
    {
        $mensajes = MensajeDestinatario::with(['mensaje.remitente'])
            ->where('destinatario_id', $request->user()->id)
            ->whereNull('eliminado_en')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('mensajes.index', compact('mensajes'));
    }

    /**
     * Bandeja de enviados del usuario autenticado.
     */
    public function enviados(Request $request): View
    {
        $mensajes = Mensaje::with(['destinatarios.destinatario'])
            ->where('remitente_id', $request->user()->id)
            ->whereDoesntHave('remitenteEliminado')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('mensajes.enviados', compact('mensajes'));
    }

    /**
     * Formulario de redacción.
     */
    public function create(Request $request): View
    {
        $usuario = $request->user();
        $destinatariosDisponibles = User::where('id', '!=', $usuario->id)
            ->where('suspendido', false)
            ->where(function ($query) {
                $query->whereIn(DB::raw('UPPER(role)'), ['GERENTE', 'ENTRENADORA', 'ADMINISTRADOR'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereRaw('UPPER(role) = ?', ['SOCIA'])
                            ->whereExists(function ($existsQuery) {
                                $existsQuery->selectRaw('1')
                                    ->from('socias')
                                    ->whereColumn('socias.user_id', 'users.id')
                                    ->where('socias.estatus', 'Activa');
                            });
                    });
            })
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        return view('mensajes.create', compact('destinatariosDisponibles'));
    }

    /**
     * Almacena el mensaje nuevo.
     */
    public function store(StoreMensajeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $mensaje = Mensaje::create([
            'remitente_id' => $request->user()->id,
            'asunto'       => $data['asunto'] ?? null,
            'cuerpo'       => $data['cuerpo'],
        ]);

        foreach ($data['destinatarios'] as $destinatarioId) {
            MensajeDestinatario::create([
                'mensaje_id'      => $mensaje->id,
                'destinatario_id' => (int) $destinatarioId,
            ]);
        }

        return redirect()
            ->route('mensajes.enviados')
            ->with('success', 'Mensaje enviado correctamente');
    }

    /**
     * Muestra un mensaje. Marca como leído si el usuario es destinatario.
     */
    public function show(Request $request, Mensaje $mensaje): View
    {
        $usuario = $request->user();

        // Verifica que el usuario tiene acceso (remitente o destinatario)
        $esDestinatario = $mensaje->destinatarios()
            ->where('destinatario_id', $usuario->id)
            ->exists();

        $esRemitente = $mensaje->remitente_id === $usuario->id;

        abort_unless($esDestinatario || $esRemitente, 403);

        // Marcar como leído automáticamente al abrir
        if ($esDestinatario) {
            $registro = $mensaje->destinatarios()
                ->where('destinatario_id', $usuario->id)
                ->first();

            $registro?->marcarComoLeido();
        }

        $mensaje->load(['remitente', 'destinatarios.destinatario']);

        return view('mensajes.show', compact('mensaje', 'esRemitente', 'esDestinatario'));
    }

    /**
     * Oculta el mensaje de la bandeja de entrada del destinatario autenticado.
     */
    public function destroyEntrada(Request $request, Mensaje $mensaje): RedirectResponse
    {
        MensajeDestinatario::where('mensaje_id', $mensaje->id)
            ->where('destinatario_id', $request->user()->id)
            ->whereNull('eliminado_en')
            ->update(['eliminado_en' => now()]);

        return redirect()
            ->route('mensajes.index')
            ->with('success', 'Mensaje eliminado de la bandeja de entrada');
    }

    /**
     * Oculta el mensaje de la bandeja de enviados del remitente autenticado.
     */
    public function destroyEnviados(Request $request, Mensaje $mensaje): RedirectResponse
    {
        abort_unless($mensaje->remitente_id === $request->user()->id, 403);

        MensajeRemitenteEliminado::firstOrCreate(
            ['mensaje_id' => $mensaje->id],
            ['eliminado_en' => now()]
        );

        return redirect()
            ->route('mensajes.enviados')
            ->with('success', 'Mensaje eliminado de la bandeja de enviados');
    }
}
