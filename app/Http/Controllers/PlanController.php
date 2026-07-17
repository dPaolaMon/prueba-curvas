<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $estatus = $request->query('estatus', 'todos');

        $query = Plan::with([
            'creadoPor',
            'actualizadoPor',
            'versiones' => function ($q) {
                $q->select('id', 'plan_id', 'nombre_comercial', 'estado_publicacion', 'vigencia_desde', 'vigencia_hasta')
                  ->orderByDesc('vigencia_desde')
                  ->orderByDesc('id');
            },
        ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($estatus !== 'todos') {
            $query->where('estatus', $estatus);
        }

        $planes = $query->orderBy('nombre', 'asc')->paginate(10)->withQueryString();

        return view('planes.index', [
            'planes' => $planes,
            'search' => $search,
            'estatus' => $estatus,
        ]);
    }

    public function create()
    {
        return view('planes.create', [
            'plan' => new Plan(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'estatus' => 'required|in:activo,inactivo',
        ]);

        $validated['creado_por'] = auth()->id();

        $plan = Plan::create($validated);

        return redirect()
            ->route('planes.show', $plan)
            ->with('success', 'Plan creado correctamente.');
    }

    public function show(Plan $plan)
    {
        $plan->load(['versiones', 'creadoPor', 'actualizadoPor']);

        return view('planes.show', [
            'plan' => $plan,
        ]);
    }

    public function edit(Request $request, Plan $plan)
    {
        $returnUrl = $this->resolveReturnUrl($request, $plan);

        return view('planes.edit', [
            'plan' => $plan,
            'cancelUrl' => $returnUrl,
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'estatus' => 'required|in:activo,inactivo',
            'return_url' => 'nullable|string',
        ]);

        $validated['actualizado_por'] = auth()->id();
        $returnUrl = $this->resolveReturnUrl($request, $plan, $validated['return_url'] ?? null);

        unset($validated['return_url']);

        $plan->update($validated);

        return redirect()
            ->to($returnUrl)
            ->with('success', 'Plan actualizado correctamente.');
    }

    private function resolveReturnUrl(Request $request, Plan $plan, ?string $returnUrl = null): string
    {
        $returnUrl = $returnUrl ?: url()->previous();
        $currentUrl = $request->fullUrl();
        $baseUrl = url('/');

        if (!str_starts_with($returnUrl, $baseUrl) || $returnUrl === $currentUrl) {
            return route('planes.show', $plan);
        }

        return $returnUrl;
    }

    public function destroy(Plan $plan)
    {
        if (!$plan->puedeBorrarse()) {
            return redirect()
                ->route('planes.index')
                ->with('error', 'No se puede eliminar un plan que tiene versiones asociadas.');
        }

        $plan->delete();

        return redirect()
            ->route('planes.index')
            ->with('success', 'Plan eliminado correctamente.');
    }
}
