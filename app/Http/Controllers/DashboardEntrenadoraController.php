<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardEntrenadoraController extends Controller
{
    public function index(Request $request): View
    {
        $role = strtoupper((string) optional($request->user())->role);

        $widgetsByRole = [
            'SOCIA' => [
                [
                    'title' => 'Próxima cita',
                    'value' => '2',
                    'note' => 'Esta semana',
                ],
                [
                    'title' => 'Asistencias del mes',
                    'value' => '9',
                    'note' => 'Meta mensual: 12',
                ],
                [
                    'title' => 'Rutina asignada',
                    'value' => 'A',
                    'note' => 'Última actualización: hoy',
                ],
                [
                    'title' => 'Vencimiento de membresía',
                    'value' => '15 días',
                    'note' => 'Renovación sugerida',
                ],
            ],
            'ENTRENADORA' => [
                [
                    'title' => 'Socias activas',
                    'value' => '128',
                    'note' => 'Meta del mes: 150',
                ],
                [
                    'title' => 'Vencen esta semana',
                    'value' => '12',
                    'note' => 'Recordatorios sugeridos',
                    'badge' => [
                        'text' => 'Seguimiento',
                        'class' => 'bg-warning text-dark',
                    ],
                ],
                [
                    'title' => 'Citas de hoy',
                    'value' => '7',
                    'note' => '4 confirmadas, 3 pendientes',
                ],
                [
                    'title' => 'Asistencia de hoy',
                    'value' => '36',
                    'note' => 'Corte a las 14:00',
                ],
            ],
            'GERENTE' => [
                [
                    'title' => 'Socias activas',
                    'value' => '128',
                    'note' => 'Meta del mes: 150',
                ],
                [
                    'title' => 'Vencen esta semana',
                    'value' => '12',
                    'note' => 'Recordatorios sugeridos',
                ],
                [
                    'title' => 'Citas de hoy',
                    'value' => '7',
                    'note' => '4 confirmadas, 3 pendientes',
                ],
                [
                    'title' => 'Ingresos (mes)',
                    'value' => '$62,400',
                    'note' => 'Última actualización: hoy',
                ],
            ],
            'ADMINISTRADOR' => [
                [
                    'title' => 'Socias activas',
                    'value' => '128',
                    'note' => 'Meta del mes: 150',
                ],
                [
                    'title' => 'Vencen esta semana',
                    'value' => '12',
                    'note' => 'Recordatorios sugeridos',
                ],
                [
                    'title' => 'Citas de hoy',
                    'value' => '7',
                    'note' => '4 confirmadas, 3 pendientes',
                ],
                [
                    'title' => 'Ingresos (mes)',
                    'value' => '$62,400',
                    'note' => 'Última actualización: hoy',
                ],
            ],
        ];

        return view('dashboards.dashboard-entrenadora', [
            'role' => $role,
            'widgets' => $widgetsByRole[$role] ?? $widgetsByRole['SOCIA'],
        ]);
    }
}
