<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use App\Models\MaquinaSemana;
use App\Models\Ejercicio;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    /**
     * Mostrar el calendario del mes actual
     */
    public function index(Request $request)
    {
        $mes = $request->query('mes', now()->month);
        $anio = $request->query('anio', now()->year);

        // Crear una fecha para trabajar con el mes/año
        $fecha = Carbon::createFromDate($anio, $mes, 1);
        
        // Datos del calendario
        $datos = $this->generarDatosCalendario($fecha);

        return view('calendario.index', [
            'mes' => $mes,
            'anio' => $anio,
            'mesNombre' => $fecha->locale('es')->monthName,
            'semanas' => $datos['semanas'],
            'diasDelMes' => $datos['diasDelMes'],
            'intervalo' => $this->generarIntervaloAnios($anio),
            'meses' => $this->obtenerMeses(),
            'todosEjercicos' => Ejercicio::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Generar datos del calendario para el mes/año
     */
    private function generarDatosCalendario(Carbon $fecha)
    {
        $primerDia = $fecha->copy()->startOfMonth();
        $ultimoDia = $fecha->copy()->endOfMonth();
        
        $semanas = [];
        $diasDelMes = [];

        // Recorrer todos los días del mes
        for ($d = $primerDia->copy(); $d <= $ultimoDia; $d->addDay()) {
            $numSemana = $d->weekOfYear;
            $dia = $d->day;
            $diaFormato = $d->format('Y-m-d');

            // Cargar eventos del día
            $eventos = $this->obtenerEventosPorDia($diaFormato);
            
            // Cargar máquinas de la semana (si no está ya registrada)
            if (!isset($semanas[$numSemana])) {
                $semanas[$numSemana] = $this->obtenerMaquinasSemana($numSemana, $fecha->month, $fecha->year);
            }

            $diasDelMes[$diaFormato] = [
                'dia' => $dia,
                'fecha' => $diaFormato,
                'numSemana' => $numSemana,
                'eventos' => $eventos,
                'esHoy' => $d->isToday(),
            ];
        }

        return [
            'semanas' => $semanas,
            'diasDelMes' => $diasDelMes,
        ];
    }

    /**
     * Obtener eventos de un día específico
     */
    private function obtenerEventosPorDia(string $dia)
    {
        return Calendario::where('dia', $dia)
                     ->orderBy('es_nota', 'asc')
                     ->get()
                     ->toArray();
    }

    /**
     * Obtener máquinas asignadas a una semana
     */
    private function obtenerMaquinasSemana(int $numSemana, int $mes, int $anio)
    {
        return MaquinaSemana::where('num_semana', $numSemana)
                            ->where('mes', $mes)
                            ->where('anio', $anio)
                            ->with('maquina')
                            ->get()
                            ->toArray();
    }

    /**
     * Generar intervalo de años para navegación
     */
    private function generarIntervaloAnios(int $anioActual)
    {
        $intervalo = [];
        for ($i = $anioActual - 6; $i <= $anioActual + 6; $i++) {
            $intervalo[] = $i;
        }
        return $intervalo;
    }

    /**
     * Obtener meses del año
     */
    private function obtenerMeses()
    {
        return [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];
    }

    /**
     * Asignar máquina a una semana
     */
    public function asignarMaquina(Request $request)
    {
        $request->validate([
            'maquina_id' => 'required|exists:maquinas,id',
            'num_semana' => 'required|integer',
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer',
        ]);

        // Verificar que no exista duplicado
        $existe = MaquinaSemana::where('maquina_id', $request->maquina_id)
                               ->where('num_semana', $request->num_semana)
                               ->where('mes', $request->mes)
                               ->where('anio', $request->anio)
                               ->exists();

        if ($existe) {
            return response()->json(['error' => 'La máquina ya está asignada a esta semana'], 422);
        }

        MaquinaSemana::create($request->only(['maquina_id', 'num_semana', 'mes', 'anio']));

        return response()->json(['success' => 'Máquina asignada correctamente']);
    }

    /**
     * Eliminar máquina de una semana
     */
    public function eliminarMaquinaSemana(Request $request, MaquinaSemana $maquinaSemana)
    {
        $maquinaSemana->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => 'Máquina eliminada de la semana']);
        }

        return redirect()->back()->with('success', 'Máquina eliminada de la semana');
    }

    /**
     * Crear evento
     */
    public function crearEvento(Request $request)
    {
        $request->validate([
            'dia' => 'required|date',
            'ejercicio' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'es_nota' => 'boolean',
        ]);

        Calendario::create($request->only(['dia', 'ejercicio', 'color', 'es_nota']));

        return response()->json(['success' => 'Evento creado correctamente']);
    }

    /**
     * Eliminar evento
     */
    public function eliminarEvento(Calendario $evento)
    {
        $evento->delete();

        return response()->json(['success' => 'Evento eliminado correctamente']);
    }
}
