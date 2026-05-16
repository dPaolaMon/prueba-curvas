<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Calendario;
use App\Models\MensajeDestinatario;
use App\Models\Socia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardSociaController extends Controller
{
    public function index(Request $request): View
    {
        $socia = $this->obtenerSociaAutenticada($request);

        $hoy = Carbon::today();
        $eventosDelDia = Calendario::whereDate('dia', $hoy)->get();
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

        return view('dashboards.dashboard-socia', [
            'socia' => $socia,
            'nombre_socia' => $socia->nombre,
            'ejercicios' => $ejerciciosDelDia,
            'notas' => $notasDelDia,
            'mensajesNoLeidos' => $mensajesNoLeidos,
            'proximoPago' => $proximoPago,
            'ultimaVisitaTexto' => $ultimaVisitaTexto,
            'asistenciasMesTotal' => $asistenciasMesTotal,
            'kioskoCalData' => $kioskoCalData,
            'mesCalendarioTitulo' => $mesCalendarioTitulo,
        ]);
    }

    public function calendarioData(Request $request)
    {
        $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer|min:2000|max:2100',
        ]);

        $socia = $this->obtenerSociaAutenticada($request);

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

    private function obtenerSociaAutenticada(Request $request): Socia
    {
        $user = $request->user();

        $socia = Socia::where('user_id', $user?->id)
            ->orWhere('email', $user?->email)
            ->first();

        if (!$socia) {
            abort(403, 'No hay un perfil de socia vinculado a esta cuenta.');
        }

        return $socia;
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
