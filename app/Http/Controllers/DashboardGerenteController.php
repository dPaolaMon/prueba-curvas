<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Socia;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardGerenteController extends Controller
{
    public function index(Request $request): View
    {
        $role = strtoupper((string) optional($request->user())->role);
        $hoy = now()->startOfDay();

        $asistenciasHoy = Asistencia::whereDate('fecha', $hoy)->count();

        $cumpleaneras = Socia::query()
            ->whereMonth('fecha_nacimiento', $hoy->month)
            ->whereDay('fecha_nacimiento', $hoy->day)
            ->orderBy('nombre')
            ->orderBy('apellidos')
            ->get(['nombre', 'apellidos'])
            ->map(function (Socia $socia): string {
                return trim($socia->nombre . ' ' . $socia->apellidos);
            })
            ->filter()
            ->values();

        $resumenCards = [
            [
                'title' => 'Ventas hoy',
                'value' => '$2,397.00',
                'button' => 'Ventas',
            ],
            [
                'title' => 'Cobros pendientes de productos',
                'value' => '$485.00',
                'button' => 'Ir a cobros',
            ],
            [
                'title' => 'Citas hoy',
                'value' => '3',
                'button' => null,
            ],
            [
                'title' => 'Asistencias hoy',
                'value' => (string) $asistenciasHoy,
                'button' => 'Ver detalle...',
            ],
        ];

        $secciones = $this->placeholderSecciones();

        return view('dashboards.dashboard-gerente', [
            'role' => $role,
            'resumenCards' => $resumenCards,
            'secciones' => $secciones,
            'cumpleaneras' => $cumpleaneras,
        ]);
    }

    private function placeholderSecciones(): Collection
    {
        return collect([
            [
                'title' => 'Socias con + de 2 inasistencias consecutivas',
                'columns' => ['Num Soc.', 'Nombre', 'Inasistencias', 'Celular'],
                'rows' => [
                    ['1045', 'Fulana del tal', '6', '55 5555 5555'],
                ],
                'footer_button' => null,
            ],
            [
                'title' => 'Socias con mensualidad pendiente de pago',
                'columns' => ['Num Soc.', 'Nombre', 'Plan', 'Monto', 'Fecha'],
                'rows' => [
                    ['1045', 'Fulana del tal por cual', 'Relax', '$599', '2'],
                    ['1105', 'Fulana del tal por cual', 'Normal', '$799', '3'],
                ],
                'footer_button' => 'Registrar cobro',
            ],
            [
                'title' => 'Citas hoy',
                'columns' => ['Hora', 'Nombre', 'Celular', 'Tipo', 'Resultado'],
                'rows' => [
                    ['7:30 am', 'Fulana del tal', '55 5555 5555', '1C', 'Asistió / Reagendar'],
                    ['7:30 am', 'Fulana del tal', '55 5555 5555', 'CM', 'Nueva socia / Reagendar'],
                ],
                'footer_button' => 'Nueva cita',
            ],
            [
                'title' => 'Gráfico de citas de CM (clase muestra) contra inscripciones que generó:',
                'columns' => [],
                'rows' => [],
                'footer_button' => null,
            ],
        ]);
    }
}
