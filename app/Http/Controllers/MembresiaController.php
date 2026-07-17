<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\Socia;
use App\Models\PlanVersion;
use App\Services\CommonDataService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

class MembresiaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $estatus = $this->normalizeEstatus($request->query('estatus', 'todos'));
        $estatusValidos = $this->getMembresiaStatuses();

        $query = Membresia::with(['socia', 'planVersion.plan', 'vendedor']);

        if ($search !== '') {
            $query->whereHas('socia', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('num_socia', 'like', "%{$search}%");
            });
        }

        if ($estatus !== 'todos' && in_array($estatus, $estatusValidos, true)) {
            $query->whereRaw('LOWER(estatus) = ?', [$estatus]);
        }

        $membresias = $query->orderBy('fecha_inicio', 'desc')->paginate(10)->withQueryString();

        return view('membresias.index', [
            'membresias' => $membresias,
            'search' => $search,
            'estatus' => $estatus,
            'estatusOpciones' => $this->getMembresiaStatusOptions(),
        ]);
    }

    public function create(Request $request)
    {
        $sociaId = $request->integer('socia_id');
        $sociaSeleccionadaId = null;

        $socias = Socia::whereRaw('LOWER(estatus) = ?', ['activa'])
            ->orderBy('nombre')
            ->get();

        if ($sociaId > 0 && $socias->contains('id', $sociaId)) {
            $sociaSeleccionadaId = $sociaId;
        }

        $planesVersiones = PlanVersion::where('estado_publicacion', 'publicado')
            ->where('vigencia_desde', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('vigencia_hasta')
                  ->orWhere('vigencia_hasta', '>=', now()->toDateString());
            })
            ->get();
        $metodosPago = CommonDataService::getPaymentMethods();

        return view('membresias.create', [
            'membresia' => new Membresia(),
            'socias' => $socias,
            'sociaSeleccionadaId' => $sociaSeleccionadaId,
            'planesVersiones' => $planesVersiones,
            'metodosPago' => $metodosPago,
        ]);
    }

    public function store(Request $request)
    {
        $estatusValidos = $this->getMembresiaStatuses();

        if ($request->filled('estatus')) {
            $request->merge([
                'estatus' => $this->normalizeEstatus($request->input('estatus')),
            ]);
        }

        $validated = $request->validate([
            'socia_id' => 'required|exists:socias,id',
            'plan_version_id' => 'required|exists:planes_versiones,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin_programada' => 'required|date|after:fecha_inicio',
            'fecha_cancelacion' => 'nullable|date',
            'fecha_renovacion' => 'nullable|date',
            'estatus' => ['nullable', 'string', Rule::in($estatusValidos)],
            'metodo_pago' => 'required|string|max:32',
            'dia_cobro' => 'nullable|integer|min:1|max:31',
            'ciclo_facturacion' => 'required|in:mensual,bimestral,trimestral,semestral,anual',
            'periodo_gracia_dias' => 'required|integer|min:0',
            'motivo_baja' => 'nullable|string|max:120',
            'observaciones' => 'nullable|string',
            'vendedor_user_id' => 'nullable|exists:users,id',
        ]);

        $validated['estatus'] = $this->defaultEstatusActiva();
        $membresia = Membresia::create($validated);

        return redirect()
            ->route('membresias.show', $membresia)
            ->with('success', 'Membresía creada correctamente.');
    }

    public function show(Membresia $membresia)
    {
        $membresia->load(['socia', 'planVersion.plan', 'vendedor', 'pagos']);

        return view('membresias.show', [
            'membresia' => $membresia,
        ]);
    }

    public function edit(Request $request, Membresia $membresia)
    {
        $socias = Socia::whereRaw('LOWER(estatus) = ?', ['activa'])
            ->orderBy('nombre')
            ->get();
        $planesVersiones = PlanVersion::all();
        $metodosPago = CommonDataService::getPaymentMethods();
        $returnUrl = $this->resolveReturnUrl($request, route('membresias.show', $membresia));

        return view('membresias.edit', [
            'membresia' => $membresia,
            'socias' => $socias,
            'planesVersiones' => $planesVersiones,
            'metodosPago' => $metodosPago,
            'estatusOpciones' => $this->getMembresiaStatusOptions(),
            'cancelUrl' => $returnUrl,
        ]);
    }

    public function update(Request $request, Membresia $membresia)
    {
        $estatusValidos = $this->getMembresiaStatuses();

        if ($request->filled('estatus')) {
            $request->merge([
                'estatus' => $this->normalizeEstatus($request->input('estatus')),
            ]);
        }

        $validated = $request->validate([
            'estatus' => ['required', 'string', Rule::in($estatusValidos)],
            'metodo_pago' => 'required|string|max:32',
            'dia_cobro' => 'nullable|integer|min:1|max:31',
            'ciclo_facturacion' => 'required|in:mensual,bimestral,trimestral,semestral,anual',
            'periodo_gracia_dias' => 'required|integer|min:0',
            'motivo_baja' => 'nullable|string|max:120',
            'observaciones' => 'nullable|string',
            'vendedor_user_id' => 'nullable|exists:users,id',
            'return_url' => 'nullable|string',
        ]);

        $returnUrl = $this->resolveReturnUrl(
            $request,
            route('membresias.show', $membresia),
            $validated['return_url'] ?? null
        );

        unset($validated['return_url']);

        $membresia->update($validated);

        return redirect()
            ->to($returnUrl)
            ->with('success', 'Membresía actualizada correctamente.');
    }

    private function resolveReturnUrl(Request $request, string $fallbackUrl, ?string $returnUrl = null): string
    {
        $returnUrl = $returnUrl ?: url()->previous();
        $currentUrl = $request->fullUrl();
        $baseUrl = url('/');

        if (!str_starts_with($returnUrl, $baseUrl) || $returnUrl === $currentUrl) {
            return $fallbackUrl;
        }

        return $returnUrl;
    }

    public function destroy(Request $request, Membresia $membresia)
    {
        if ($membresia->pagos()->count() > 0) {
            return redirect()
                ->route('membresias.index')
                ->with('error', 'No se puede eliminar una membresía que tiene pagos registrados.');
        }

        $membresia->delete();

        return redirect()
            ->route('membresias.index')
            ->with('success', 'Membresía eliminada correctamente.');
    }

    private function getMembresiaStatuses(): array
    {
        $rawStatuses = CommonDataService::getMembresiaEstatus();

        if (empty($rawStatuses)) {
            throw new RuntimeException('No hay estatus de membresia configurados en resources/data/common_data.json');
        }

        $estatus = array_map(fn ($valor) => $this->normalizeEstatus($valor), $rawStatuses);
        $estatus = array_values(array_unique(array_filter($estatus)));

        if (empty($estatus)) {
            throw new RuntimeException('Los estatus de membresia configurados en JSON son invalidos.');
        }

        return $estatus;
    }

    private function getMembresiaStatusOptions(): array
    {
        $rawStatuses = CommonDataService::getMembresiaEstatus();

        if (empty($rawStatuses)) {
            throw new RuntimeException('No hay estatus de membresia configurados en resources/data/common_data.json');
        }

        return array_map(function ($status) {
            return [
                'value' => $this->normalizeEstatus($status),
                'label' => ucfirst($this->normalizeEstatus($status)),
            ];
        }, $rawStatuses);
    }

    private function normalizeEstatus(mixed $estatus): string
    {
        return mb_strtolower(trim((string) $estatus));
    }

    private function defaultEstatusActiva(): string
    {
        return $this->resolveEstatus('activa');
    }

    private function resolveEstatus(string $preferido): string
    {
        $estatus = $this->getMembresiaStatuses();

        if (!in_array($preferido, $estatus, true)) {
            throw new RuntimeException("El estatus '{$preferido}' no existe en resources/data/common_data.json");
        }

        return $preferido;
    }
}
