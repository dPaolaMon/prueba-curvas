<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Socia;
use App\Models\KioskoSession;
use App\Models\Calendario;
use App\Models\MensajeDestinatario;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class KioskoController extends Controller
{
    /**
     * Mostrar el kiosko de asistencia
     */
    public function panel(Request $request)
    {
        $kioskoSession = $this->validarTokenKiosko($request);

        // Reiniciar el estado del ciclo de toasts al volver al panel del kiosko
        session()->forget("kiosko.toast_once.{$kioskoSession->token}");

        return view('kiosko.panel');
    }

    public function inicio(Request $request)
    {
        $kioskoSession = $this->validarTokenKiosko($request);

        $request->validate([
            'num_socia' => 'required|integer|min:1',
        ]);

        $socia = Socia::where('num_socia', $request->query('num_socia'))->first();

        if (!$socia) {
            abort(404, 'Número de socia no encontrado.');
        }

        if ($socia->estatus !== 'Activa') {
            abort(403, 'Membresía dada de baja.');
        }

        $hoyKey = now()->toDateString();
        $toastSessionKey = "kiosko.toast_once.{$kioskoSession->token}.{$hoyKey}.{$socia->num_socia}";
        $toastAsistencia = null;

        if (!session()->has($toastSessionKey)) {
            $resultadoAsistencia = $this->registrarAsistenciaSocia($socia);

            $toastAsistencia = [
                'icon' => $resultadoAsistencia['status'] === 'registrada' ? 'success' : 'info',
                'message' => $resultadoAsistencia['message'],
            ];

            session([$toastSessionKey => true]);
        }

        // Obtener eventos del día
        $hoy = Carbon::today();
        $eventosDelDia = Calendario::whereDate('dia', $hoy)->get();
        
        // Separar eventos por tipo
        $ejerciciosDelDia = $eventosDelDia->where('es_nota', false);
        $notasDelDia = $eventosDelDia->where('es_nota', true);

        $datosCalendarioMes = $this->construirDatosCalendario($socia, $hoy);
        $asistenciasMesTotal = $datosCalendarioMes['asistenciasMesTotal'];
        $kioskoCalData = $datosCalendarioMes['kioskoCalData'];
        $mesCalendarioTitulo = $datosCalendarioMes['mesCalendarioTitulo'];

        $ultimaAsistencia = Asistencia::where('socia_id', $socia->id)
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->first();

        $ultimaVisitaTexto = $ultimaAsistencia
            ? $ultimaAsistencia->fecha->locale('es')->diffForHumans(now())
            : 'Sin registros de asistencia';

        $mensajesNoLeidos = collect();
        if ($socia->user_id) {
            $mensajesNoLeidos = MensajeDestinatario::with(['mensaje.remitente'])
                ->where('destinatario_id', $socia->user_id)
                ->whereNull('leido_en')
                ->whereNull('eliminado_en')
                ->orderByDesc('created_at')
                ->take(5)
                ->get();
        }

        $proximoPago = null;
        if ($socia->fecha_reingreso) {
            $proximoPago = $socia->fecha_reingreso->copy();
            while ($proximoPago->lessThanOrEqualTo($hoy)) {
                $proximoPago->addMonthNoOverflow();
            }
        }

        $medidasRecientes = $socia->medidas()
            ->orderByDesc('fecha_registro')
            ->orderByDesc('id')
            ->take(2)
            ->get();

        $medidaActual = $medidasRecientes->get(0);
        $medidaAnterior = $medidasRecientes->get(1);

        $camposMedidas = [
            'busto' => ['label' => 'Busto', 'unidad' => 'cm'],
            'cintura' => ['label' => 'Cintura', 'unidad' => 'cm'],
            'abdomen' => ['label' => 'Abdomen', 'unidad' => 'cm'],
            'caderas' => ['label' => 'Caderas', 'unidad' => 'cm'],
            'muslo' => ['label' => 'Muslo', 'unidad' => 'cm'],
            'brazo' => ['label' => 'Brazo', 'unidad' => 'cm'],
            'peso' => ['label' => 'Peso', 'unidad' => 'kg'],
            'altura' => ['label' => 'Altura', 'unidad' => 'cm'],
            'porcentaje_grasa' => ['label' => '% Grasa', 'unidad' => '%'],
        ];

        $resumenMedidas = collect($camposMedidas)->map(function (array $meta, string $campo) use ($medidaActual, $medidaAnterior) {
            $actual = $medidaActual?->{$campo};
            $anterior = $medidaAnterior?->{$campo};

            return [
                'label' => $meta['label'],
                'unidad' => $meta['unidad'],
                'actual' => $actual,
                'anterior' => $anterior,
                'diferencia' => is_null($actual) || is_null($anterior) ? null : round($actual - $anterior, 2),
            ];
        })->values();

        $cmPerdidos = 0.0;
        $pesoPerdido = null;

        if ($medidaActual && $medidaAnterior) {
            foreach (['busto', 'cintura', 'abdomen', 'caderas', 'muslo', 'brazo'] as $campoCm) {
                $delta = (float) $medidaAnterior->{$campoCm} - (float) $medidaActual->{$campoCm};
                if ($delta > 0) {
                    $cmPerdidos += $delta;
                }
            }

            $pesoPerdido = round((float) $medidaAnterior->peso - (float) $medidaActual->peso, 3);
        }

        $mensajeProgreso = 'Aún no hay suficientes mediciones para comparar progreso.';
        if ($medidaActual && $medidaAnterior) {
            if ($cmPerdidos > 0 || ($pesoPerdido ?? 0) > 0) {
                $mensajeProgreso = sprintf(
                    '¡Felicidades! En tu última comparación perdiste %.2f cm y %.3f kg.',
                    $cmPerdidos,
                    max(0, (float) $pesoPerdido)
                );
            } else {
                $mensajeProgreso = 'En la última comparación no se reflejó reducción en cm o peso; sigue así, tu constancia cuenta.';
            }
        }

        $canvasMedidasAnterior = [
            'brazos' => $medidaAnterior ? (float) $medidaAnterior->brazo : 0,
            'busto' => $medidaAnterior ? (float) $medidaAnterior->busto : 0,
            'cintura' => $medidaAnterior ? (float) $medidaAnterior->cintura : 0,
            'abdomen' => $medidaAnterior ? (float) $medidaAnterior->abdomen : 0,
            'cadera' => $medidaAnterior ? (float) $medidaAnterior->caderas : 0,
            'muslos' => $medidaAnterior ? (float) $medidaAnterior->muslo : 0,
            'papada' => 0,
        ];

        $canvasMedidasActual = [
            'brazos' => $medidaActual ? (float) $medidaActual->brazo : 26,
            'busto' => $medidaActual ? (float) $medidaActual->busto : 83,
            'cintura' => $medidaActual ? (float) $medidaActual->cintura : 60,
            'abdomen' => $medidaActual ? (float) $medidaActual->abdomen : 58,
            'cadera' => $medidaActual ? (float) $medidaActual->caderas : 78,
            'muslos' => $medidaActual ? (float) $medidaActual->muslo : 41,
            'papada' => 0,
        ];

        return view('kiosko.inicio', [
            'token' => $request->query('token'),
            'num_socia' => $request->query('num_socia'),
            'socia' => $socia,
            'nombre_socia' => $socia->nombre,
            'ejercicios' => $ejerciciosDelDia,
            'notas' => $notasDelDia,
            'mensajesNoLeidos' => $mensajesNoLeidos,
            'toastAsistencia' => $toastAsistencia,
            'proximoPago' => $proximoPago,
            'ultimaVisitaTexto' => $ultimaVisitaTexto,
            'asistenciasMesTotal' => $asistenciasMesTotal,
            'kioskoCalData' => $kioskoCalData,
            'mesCalendarioTitulo' => $mesCalendarioTitulo,
            'medidaActual' => $medidaActual,
            'medidaAnterior' => $medidaAnterior,
            'resumenMedidas' => $resumenMedidas,
            'mensajeProgreso' => $mensajeProgreso,
            'canvasMedidasAnterior' => $canvasMedidasAnterior,
            'canvasMedidasActual' => $canvasMedidasActual,
        ]);
    }

    public function calendarioData(Request $request)
    {
        $this->validarTokenKiosko($request);

        $request->validate([
            'num_socia' => 'required|integer|min:1',
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer|min:2000|max:2100',
        ]);

        $socia = Socia::where('num_socia', $request->integer('num_socia'))->first();

        if (!$socia) {
            return response()->json([
                'error' => 'Número de socia no encontrado.',
            ], 404);
        }

        if ($socia->estatus !== 'Activa') {
            return response()->json([
                'error' => 'Membresía dada de baja.',
            ], 403);
        }

        $fechaBase = Carbon::createFromDate(
            $request->integer('año'),
            $request->integer('mes'),
            1
        )->startOfDay();

        $datosCalendarioMes = $this->construirDatosCalendario($socia, $fechaBase);

        return response()->json([
            'success' => true,
            'kioskoCalData' => $datosCalendarioMes['kioskoCalData'],
            'mesCalendarioTitulo' => $datosCalendarioMes['mesCalendarioTitulo'],
            'asistenciasMesTotal' => $datosCalendarioMes['asistenciasMesTotal'],
        ]);
    }

    /**
     * Buscar socia por num_socia (sin autenticación)
     */
    public function buscar(Request $request)
    {
        $this->validarTokenKiosko($request);

        $request->validate([
            'num_socia' => 'required|integer|min:1',
        ]);

        $socia = Socia::where('num_socia', $request->num_socia)->first();

        if (!$socia) {
            return response()->json([
                'error' => 'Número de socia no encontrado.'
            ], 404);
        }

        if ($socia->estatus !== 'Activa') {
            return response()->json([
                'error' => 'Tu membresía está dada de baja. Acércate a recepción para más información.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'socia' => [
                'num_socia' => $socia->num_socia,
                'nombre'    => $socia->nombre,
                'apellidos' => $socia->apellidos,
                'foto'      => $socia->foto ? asset('storage/' . $socia->foto) : null,
            ]
        ]);
    }

    private function validarTokenKiosko(Request $request): KioskoSession
    {
        $token = $request->query('token');

        if (!$token) {
            abort(401, 'Token de kiosko requerido.');
        }

        $kioskoSession = KioskoSession::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$kioskoSession) {
            abort(401, 'Token de kiosko inválido o expirado.');
        }

        return $kioskoSession;
    }

    public function kioskoIniciar()
    {
        // Crear una sesión de kiosko que persista
        $kioskoSession = KioskoSession::create([
            'user_id' => auth()->id(),
            'token' => KioskoSession::generateToken(),
            'expires_at' => now()->addDay()->startOfDay(), // Expira a medianoche
        ]);

        return response()->json([
            'success' => true,
            'url' => route('kiosko.panel', ['token' => $kioskoSession->token]),
        ]);
    }

    public function asistencia(Request $request)
    {
        // Validar token de kiosko si no hay sesión autenticada
        if (!auth()->check()) {
            $token = $request->query('token');
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de kiosko requerido.',
                ], 401);
            }

            $kioskoSession = KioskoSession::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();

            if (!$kioskoSession) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de kiosko inválido o expirado.',
                ], 401);
            }
        }

        try {
            $request->validate([
                'num_socia' => 'required|exists:socias,num_socia',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Número de socia no encontrado.',
            ], 422);
        }

        $numSocia = $request->input('num_socia');

        // Buscar la socia por num_socia
        $socia = Socia::where('num_socia', $numSocia)->first();

        if (!$socia) {
            return response()->json([
                'success' => false,
                'message' => 'Número de socia no encontrado.',
            ], 404);
        }

        $resultado = $this->registrarAsistenciaSocia($socia);

        if ($resultado['status'] === 'ya_registrada') {
            return response()->json([
                'success' => false,
                'message' => $resultado['message'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'socia' => [
                'id' => $socia->id,
                'num_socia' => $socia->num_socia,
                'nombre' => $socia->nombre,
                'apellidos' => $socia->apellidos,
            ],
            'message' => $resultado['message'],
        ]);
    }

    private function registrarAsistenciaSocia(Socia $socia): array
    {
        $hoy = Carbon::today();

        $asistenciaExistente = Asistencia::where('socia_id', $socia->id)
            ->whereDate('fecha', $hoy)
            ->first();

        if ($asistenciaExistente) {
            return [
                'status' => 'ya_registrada',
                'message' => 'Ya has registrado asistencia hoy.',
            ];
        }

        Asistencia::create([
            'socia_id' => $socia->id,
            'fecha' => $hoy,
            'hora' => now()->toTimeString(),
        ]);

        return [
            'status' => 'registrada',
            'message' => '¡Asistencia registrada!',
        ];
    }

    private function construirDatosCalendario(Socia $socia, Carbon $fechaBase): array
    {
        $inicioMes = $fechaBase->copy()->startOfMonth();
        $finMes = $fechaBase->copy()->endOfMonth();

        $asistenciasMes = Asistencia::where('socia_id', $socia->id)
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->orderBy('fecha')
            ->get();

        $asistenciasMesSet = array_flip(
            $asistenciasMes
                ->map(fn (Asistencia $asistencia) => $asistencia->fecha->toDateString())
                ->all()
        );

        $eventosMes = Calendario::whereBetween('dia', [$inicioMes, $finMes])
            ->where('es_nota', false)
            ->orderBy('dia')
            ->get();

        $actividadesMes = [];

        foreach ($eventosMes as $evento) {
            $diaNumero = (int) $evento->dia->format('j');

            if (!isset($actividadesMes[$diaNumero])) {
                $actividadesMes[$diaNumero] = [
                    'nombres' => [],
                    'asistio' => false,
                ];
            }

            $actividadesMes[$diaNumero]['nombres'][] = Str::limit((string) $evento->ejercicio, 14, '...');
            $actividadesMes[$diaNumero]['asistio'] = isset($asistenciasMesSet[$evento->dia->toDateString()]);
        }

        foreach ($asistenciasMes as $asistencia) {
            $diaNumero = (int) $asistencia->fecha->format('j');

            if (!isset($actividadesMes[$diaNumero])) {
                $actividadesMes[$diaNumero] = [
                    'nombres' => ['Asistencia'],
                    'asistio' => true,
                ];
                continue;
            }

            $actividadesMes[$diaNumero]['asistio'] = true;
        }

        return [
            'kioskoCalData' => [
                'mes' => (int) $fechaBase->format('n'),
                'año' => (int) $fechaBase->format('Y'),
                'actividades' => $actividadesMes,
            ],
            'mesCalendarioTitulo' => Str::ucfirst($fechaBase->copy()->locale('es')->translatedFormat('F Y')),
            'asistenciasMesTotal' => $asistenciasMes->count(),
        ];
    }


}
