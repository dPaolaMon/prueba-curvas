<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Socia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $socias = Socia::where('estatus', 'Activa')
            ->orderBy('num_socia')
            ->get();

        $sociaSeleccionada = null;
        $asistenciasRecientes = collect();
        $asistenciaEnFecha = null;

        if ($request->filled('socia_id') && $request->filled('fecha')) {
            $sociaId = $request->input('socia_id');
            $fecha = $request->input('fecha');

            $sociaSeleccionada = Socia::find($sociaId);
            
            if ($sociaSeleccionada) {
                // Obtener las últimas 10 asistencias de la socia
                $asistenciasRecientes = Asistencia::where('socia_id', $sociaId)
                    ->orderBy('fecha', 'desc')
                    ->take(10)
                    ->get();

                // Verificar si existe asistencia en la fecha seleccionada
                $asistenciaEnFecha = Asistencia::where('socia_id', $sociaId)
                    ->whereDate('fecha', $fecha)
                    ->first();
            }
        }

        $hora = $request->input('hora');

        if (empty($hora) && $asistenciaEnFecha) {
            $hora = Carbon::parse($asistenciaEnFecha->hora)->format('H:i');
        }

        if (empty($hora)) {
            $hora = now()->format('H:i');
        }

        return view('asistencia.index', [
            'socias' => $socias,
            'sociaSeleccionada' => $sociaSeleccionada,
            'asistenciasRecientes' => $asistenciasRecientes,
            'asistenciaEnFecha' => $asistenciaEnFecha,
            'sociaId' => $request->input('socia_id'),
            'fecha' => $request->input('fecha'),
            'hora' => $hora,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'socia_id' => 'required|exists:socias,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
        ]);

        $socia = Socia::findOrFail($request->input('socia_id'));
        $fecha = $request->input('fecha');
        $hora = $request->input('hora');

        if ($socia->estatus !== 'Activa') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede registrar asistencia: la socia está dada de baja.',
            ], 422);
        }

        // Verificar si ya existe asistencia
        $asistenciaExistente = Asistencia::where('socia_id', $socia->id)
            ->whereDate('fecha', $fecha)
            ->first();

        if (!$asistenciaExistente) {
            Asistencia::create([
                'socia_id' => $socia->id,
                'fecha' => $fecha,
                'hora' => $hora . ':00',
            ]);

            return response()->json([
                'success' => true,
                'message' => "Asistencia registrada para {$socia->nombre} {$socia->apellidos} el " . Carbon::parse($fecha)->locale('es')->isoFormat('DD/MM/YYYY') . " a las {$hora}",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'La asistencia para esta socia en esta fecha ya está registrada',
        ], 422);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'socia_id' => 'required|exists:socias,id',
            'fecha' => 'required|date',
        ]);

        $asistencia = Asistencia::where('socia_id', $request->input('socia_id'))
            ->whereDate('fecha', $request->input('fecha'))
            ->first();

        if ($asistencia) {
            $socia = $asistencia->socia;
            $asistencia->delete();

            return response()->json([
                'success' => true,
                'message' => "Asistencia eliminada para {$socia->nombre} {$socia->apellidos} el " . Carbon::parse($request->input('fecha'))->locale('es')->isoFormat('DD/MM/YYYY'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontró asistencia para eliminar',
        ], 404);
    }

    public function verificar(Request $request)
    {
        $request->validate([
            'socia_id' => 'required|exists:socias,id',
            'fecha' => 'required|date',
        ]);

        $asistencia = Asistencia::where('socia_id', $request->input('socia_id'))
            ->whereDate('fecha', $request->input('fecha'))
            ->first();

        return response()->json([
            'existe' => (bool) $asistencia,
        ]);
    }
}
