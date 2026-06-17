<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Socia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteAsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', '1');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $search = trim((string) $request->get('search', ''));
        $sort = $request->get('sort', 'total');
        $direction = strtolower((string) $request->get('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        if (!in_array($sort, ['total', 'num_socia', 'nombre'], true)) {
            $sort = 'total';
        }

        // Determinar las fechas según el filtro
        [$fechaInicio, $fechaFin] = $this->calcularFechas($filtro, $fechaInicio, $fechaFin);

        // Determinar si mostrar detalle de fechas (solo para opciones 1, 2, 3)
        $mostrarDetalle = in_array($filtro, ['1', '2', '3']);

        $asistenciasHoy = Asistencia::with('socia')
            ->whereDate('fecha', Carbon::today())
            ->whereHas('socia', function ($query) {
                $query->where('estatus', 'Activa');
            })
            ->orderBy('hora')
            ->get()
            ->map(function ($asistencia) {
                return [
                    'num_socia' => $asistencia->socia?->num_socia,
                    'nombre' => trim(($asistencia->socia?->nombre ?? '') . ' ' . ($asistencia->socia?->apellidos ?? '')),
                    'hora' => optional($asistencia->hora)->format('H:i'),
                ];
            })
            ->values();

        $hoy = Carbon::today();
        $ausenciasConsecutivas = Socia::where('estatus', 'Activa')
            ->withMax('asistencias as ultima_fecha_asistencia', 'fecha')
            ->orderBy('num_socia')
            ->get()
            ->map(function ($socia) use ($hoy) {
                $fechaReferencia = $socia->ultima_fecha_asistencia
                    ? Carbon::parse($socia->ultima_fecha_asistencia)
                    : Carbon::parse($socia->fecha_reingreso ?? $socia->fecha_alta ?? $hoy->format('Y-m-d'));

                $diasAusencia = $fechaReferencia->diffInDays($hoy);

                return [
                    'num_socia' => $socia->num_socia,
                    'nombre' => trim($socia->nombre . ' ' . $socia->apellidos),
                    'dias_ausencia' => $diasAusencia,
                    'ultima_asistencia' => $socia->ultima_fecha_asistencia
                        ? Carbon::parse($socia->ultima_fecha_asistencia)->locale('es')->isoFormat('DD/MM/YYYY')
                        : 'Sin asistencias registradas',
                ];
            })
            ->filter(function ($fila) {
                return $fila['dias_ausencia'] > 2;
            })
            ->sortByDesc('dias_ausencia')
            ->values();

        // Obtener todas las socias activas
        $socias = Socia::where('estatus', 'Activa')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('num_socia', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%")
                        ->orWhere('apellidos', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(nombre, ' ', apellidos) like ?", ["%{$search}%"]);
                });
            })
            ->orderBy('num_socia')
            ->get();

        // Obtener asistencias en el rango de fechas
        $asistencias = Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get()
            ->groupBy('socia_id');

        // Obtener fechas únicas en el rango (para columnas dinámicas)
        $fechas = [];
        if ($mostrarDetalle) {
            $fechas = Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->select('fecha')
                ->distinct()
                ->orderBy('fecha')
                ->pluck('fecha')
                ->map(function ($fecha) {
                    return Carbon::parse($fecha);
                });
        }

        // Preparar datos para la tabla
        $datosReporte = [];
        foreach ($socias as $socia) {
            $asistenciasSocia = $asistencias->get($socia->id, collect());
            
            $fila = [
                'num_socia' => $socia->num_socia,
                'nombre' => trim($socia->nombre . ' ' . $socia->apellidos),
                'asistencias_detalle' => [],
                'total' => $asistenciasSocia->count(),
            ];

            if ($mostrarDetalle) {
                // Crear array de fechas con asistencias
                $asistenciasPorFecha = $asistenciasSocia->keyBy(function ($item) {
                    return Carbon::parse($item->fecha)->format('Y-m-d');
                });

                foreach ($fechas as $fecha) {
                    $fechaStr = $fecha->format('Y-m-d');
                    $fila['asistencias_detalle'][$fechaStr] = isset($asistenciasPorFecha[$fechaStr]);
                }
            }

            $datosReporte[] = $fila;
        }

        usort($datosReporte, function (array $a, array $b) use ($sort, $direction) {
            if ($sort === 'nombre') {
                $cmp = strcmp((string) $a['nombre'], (string) $b['nombre']);
                return $direction === 'asc' ? $cmp : -$cmp;
            }

            $valueA = $sort === 'total' ? (int) $a['total'] : (int) $a['num_socia'];
            $valueB = $sort === 'total' ? (int) $b['total'] : (int) $b['num_socia'];

            if ($valueA === $valueB) {
                return (int) $a['num_socia'] <=> (int) $b['num_socia'];
            }

            $cmp = $valueA <=> $valueB;
            return $direction === 'asc' ? $cmp : -$cmp;
        });

        return view('reportes.asistencia', [
            'datosReporte' => $datosReporte,
            'fechas' => $fechas,
            'mostrarDetalle' => $mostrarDetalle,
            'filtro' => $filtro,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'asistenciasHoy' => $asistenciasHoy,
            'ausenciasConsecutivas' => $ausenciasConsecutivas,
        ]);
    }

    public function export(Request $request)
    {
        $filtro = $request->get('filtro', '1');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $search = trim((string) $request->get('search', ''));

        // Determinar las fechas según el filtro
        [$fechaInicio, $fechaFin] = $this->calcularFechas($filtro, $fechaInicio, $fechaFin);

        // Obtener todas las socias activas
        $socias = Socia::where('estatus', 'Activa')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('num_socia', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%")
                        ->orWhere('apellidos', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(nombre, ' ', apellidos) like ?", ["%{$search}%"]);
                });
            })
            ->orderBy('num_socia')
            ->get();

        // Obtener asistencias en el rango de fechas
        $asistencias = Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get()
            ->groupBy('socia_id');

        // Obtener todas las fechas en el rango
        $fechas = Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('fecha')
            ->distinct()
            ->orderBy('fecha')
            ->pluck('fecha')
            ->map(function ($fecha) {
                return Carbon::parse($fecha);
            });

        // Preparar CSV
        $filename = 'reporte_asistencia_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($socias, $asistencias, $fechas) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            $encabezados = ['Num Socia', 'Nombre'];
            foreach ($fechas as $fecha) {
                $encabezados[] = $fecha->locale('es')->isoFormat('DD/MM/YYYY (ddd)');
            }
            $encabezados[] = 'Total Asistencias';
            fputcsv($file, $encabezados);

            // Datos
            foreach ($socias as $socia) {
                $asistenciasSocia = $asistencias->get($socia->id, collect());
                $asistenciasPorFecha = $asistenciasSocia->keyBy(function ($item) {
                    return Carbon::parse($item->fecha)->format('Y-m-d');
                });

                $fila = [
                    $socia->num_socia,
                    trim($socia->nombre . ' ' . $socia->apellidos),
                ];

                foreach ($fechas as $fecha) {
                    $fechaStr = $fecha->format('Y-m-d');
                    $fila[] = isset($asistenciasPorFecha[$fechaStr]) ? '✓' : '';
                }

                $fila[] = $asistenciasSocia->count();

                fputcsv($file, $fila);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function calcularFechas($filtro, $fechaInicio = null, $fechaFin = null)
    {
        $hoy = Carbon::today();

        switch ($filtro) {
            case '1': // La presente semana
                $inicio = $hoy->copy()->startOfWeek(Carbon::MONDAY);
                $fin = $hoy->copy()->endOfWeek(Carbon::SUNDAY);
                break;

            case '2': // Lo que va del mes
                $inicio = $hoy->copy()->startOfMonth();
                $fin = $hoy->copy();
                break;

            case '3': // Mes anterior
                $inicio = $hoy->copy()->subMonth()->startOfMonth();
                $fin = $hoy->copy()->subMonth()->endOfMonth();
                break;

            case '4': // Últimos dos meses
                $inicio = $hoy->copy()->subMonths(2)->startOfMonth();
                $fin = $hoy->copy();
                break;

            case '5': // Último semestre
                $inicio = $hoy->copy()->subMonths(6)->startOfMonth();
                $fin = $hoy->copy();
                break;

            case '6': // Lo que va del año
                $inicio = $hoy->copy()->startOfYear();
                $fin = $hoy->copy();
                break;

            case '7': // Personalizado
                if ($fechaInicio && $fechaFin) {
                    $inicio = Carbon::parse($fechaInicio);
                    $fin = Carbon::parse($fechaFin);
                } else {
                    $inicio = $hoy->copy()->startOfMonth();
                    $fin = $hoy->copy();
                }
                break;

            default:
                $inicio = $hoy->copy()->startOfWeek(Carbon::MONDAY);
                $fin = $hoy->copy()->endOfWeek(Carbon::SUNDAY);
        }

        return [$inicio->format('Y-m-d'), $fin->format('Y-m-d')];
    }
}
