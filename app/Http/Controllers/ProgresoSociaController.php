<?php

namespace App\Http\Controllers;

use App\Models\Socia;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgresoSociaController extends Controller
{
    public function index(Request $request): View
    {
        $socia = $this->obtenerSociaAutenticada($request);

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

        return view('progreso.para-socia', [
            'socia' => $socia,
            'nombre_socia' => $socia->nombre,
            'medidaActual' => $medidaActual,
            'medidaAnterior' => $medidaAnterior,
            'resumenMedidas' => $resumenMedidas,
            'mensajeProgreso' => $mensajeProgreso,
            'canvasMedidasAnterior' => $canvasMedidasAnterior,
            'canvasMedidasActual' => $canvasMedidasActual,
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
}
