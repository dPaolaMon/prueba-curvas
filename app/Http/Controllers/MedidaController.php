<?php

namespace App\Http\Controllers;

use App\Models\Medida;
use App\Models\Socia;
use App\Http\Requests\StoreMedidaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MedidaController extends Controller
{
    private const CAMPOS_MEDIDAS = [
        'busto' => ['label' => 'Busto', 'unidad' => 'cm'],
        'cintura' => ['label' => 'Cintura', 'unidad' => 'cm'],
        'abdomen' => ['label' => 'Abdomen', 'unidad' => 'cm'],
        'caderas' => ['label' => 'Caderas', 'unidad' => 'cm'],
        'muslo' => ['label' => 'Muslo', 'unidad' => 'cm'],
        'brazo' => ['label' => 'Brazo', 'unidad' => 'cm'],
        'peso' => ['label' => 'Peso', 'unidad' => 'kg'],
        'altura' => ['label' => 'Altura', 'unidad' => 'cm'],
        'imc' => ['label' => 'IMC', 'unidad' => ''],
        'porcentaje_grasa' => ['label' => '% Grasa', 'unidad' => '%'],
    ];

    /**
     * Display a listing of the measures.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'fecha_registro');
        $direction = strtolower((string) $request->query('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['fecha_registro'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'fecha_registro';
        }

        $medidas = Medida::with('socia')
            ->orderBy($sort, $direction)
            ->get();

        return view('medidas.index', compact('medidas', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new measure.
     */
    public function create(Request $request)
    {
        $medida = new Medida();
        $sociaId = $request->integer('socia_id');
        $returnTo = $this->resolverRetorno($request);

        if ($sociaId > 0 && Socia::whereKey($sociaId)->exists()) {
            $medida->socia_id = $sociaId;
        }

        $socias = Socia::orderBy('nombre')->orderBy('apellidos')->get();

        return view('medidas.create', compact('medida', 'socias', 'returnTo'));
    }

    /**
     * Store a newly created measure in storage.
     */
    public function store(StoreMedidaRequest $request)
    {
        $validated = $request->validated();
        $returnTo = $this->resolverRetorno($request);

        Medida::create($validated);

        return redirect()->to($returnTo)
                        ->with('success', 'Medida registrada correctamente');
    }

    /**
     * Show the form for editing the specified measure.
     */
    public function edit(Request $request, Medida $medida)
    {
        $socias = Socia::orderBy('nombre')->orderBy('apellidos')->get();
        $returnTo = $this->resolverRetorno($request);

        return view('medidas.edit', compact('medida', 'socias', 'returnTo'));
    }

    /**
     * Display the measure history for the selected member.
     */
    public function historial(Socia $socia)
    {
        $medidas = $this->obtenerMedidasDeSocia($socia);
        $medidaActual = $medidas->first();
        $medidaAnterior = $medidas->skip(1)->first();

        $resumenComparativo = collect(self::CAMPOS_MEDIDAS)->map(function (array $meta, string $campo) use ($medidaActual, $medidaAnterior) {
            $actual = $medidaActual?->{$campo};
            $anterior = $medidaAnterior?->{$campo};

            return [
                'campo' => $campo,
                'label' => $meta['label'],
                'unidad' => $meta['unidad'],
                'actual' => $actual,
                'anterior' => $anterior,
                'diferencia' => is_null($actual) || is_null($anterior) ? null : round((float) $actual - (float) $anterior, 2),
            ];
        })->values();

        return view('medidas.historial', [
            'socia' => $socia,
            'medidas' => $medidas,
            'medidaActual' => $medidaActual,
            'medidaAnterior' => $medidaAnterior,
            'resumenComparativo' => $resumenComparativo,
            'camposMedidas' => self::CAMPOS_MEDIDAS,
        ]);
    }

    /**
     * Export the measure history for the selected member.
     */
    public function exportHistorial(Socia $socia)
    {
        $medidas = $this->obtenerMedidasDeSocia($socia);
        $filename = 'historial_medidas_' . $socia->num_socia . '_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($socia, $medidas) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Num Socia',
                'Nombre',
                'Fecha registro',
                'Busto (cm)',
                'Cintura (cm)',
                'Abdomen (cm)',
                'Caderas (cm)',
                'Muslo (cm)',
                'Brazo (cm)',
                'Peso (kg)',
                'Altura (cm)',
                'IMC',
                '% Grasa',
            ]);

            foreach ($medidas as $medida) {
                fputcsv($file, [
                    $socia->num_socia,
                    trim($socia->nombre . ' ' . $socia->apellidos),
                    optional($medida->fecha_registro)->format('d/m/Y H:i'),
                    $medida->busto,
                    $medida->cintura,
                    $medida->abdomen,
                    $medida->caderas,
                    $medida->muslo,
                    $medida->brazo,
                    $medida->peso,
                    $medida->altura,
                    $medida->imc,
                    $medida->porcentaje_grasa,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Update the specified measure in storage.
     */
    public function update(StoreMedidaRequest $request, Medida $medida)
    {
        $validated = $request->validated();
        $returnTo = $this->resolverRetorno($request);

        $medida->update($validated);

        return redirect()->to($returnTo)
                        ->with('success', 'Medida actualizada correctamente');
    }

    /**
     * Remove the specified measure from storage.
     */
    public function destroy(Medida $medida)
    {
        $medida->delete();

        return redirect()->route('medidas.index')
                        ->with('success', 'Medida eliminada correctamente');
    }

    private function obtenerMedidasDeSocia(Socia $socia)
    {
        return $socia->medidas()
            ->orderByDesc('fecha_registro')
            ->orderByDesc('id')
            ->get();
    }

    private function resolverRetorno(Request $request): string
    {
        $default = route('medidas.index');
        $returnTo = trim((string) ($request->input('return_to') ?? $request->query('return_to', '')));

        if ($returnTo === '') {
            return $default;
        }

        if (str_starts_with($returnTo, '/')) {
            return url($returnTo);
        }

        $returnHost = parse_url($returnTo, PHP_URL_HOST);
        $appHost = parse_url(url('/'), PHP_URL_HOST);

        if (!empty($returnHost) && !empty($appHost) && $returnHost === $appHost) {
            return $returnTo;
        }

        return $default;
    }
}
