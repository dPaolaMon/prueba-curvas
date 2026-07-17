<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanVersion;
use Illuminate\Http\Request;

class PlanVersionController extends Controller
{
    public function index(Request $request, Plan $plan)
    {
        $versiones = $plan->versiones()
            ->orderBy('vigencia_desde', 'desc')
            ->paginate(10);

        /*$backUrl = $this->resolveReturnUrl(
            $request,
            route('planes.show', $plan),
            $request->query('return_to')
        );*/

        return view('planes-versiones.index', [
            'plan' => $plan,
            'versiones' => $versiones,
            //'backUrl' => $backUrl,
        ]);
    }

    public function create(Request $request, Plan $plan)
    {
        $cancelUrl = route('planes.planes-versiones.index', $plan);

        return view('planes-versiones.create', [
            'plan' => $plan,
            'planVersion' => new PlanVersion(),
            //'cancelUrl' => $cancelUrl,
        ]);
    }

    public function store(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'nombre_comercial' => 'required|string|max:150',
            'vigencia_desde' => 'required|date',
            'vigencia_hasta' => 'nullable|date|after:vigencia_desde',
            'precio_inscripcion' => 'required|numeric|min:0',
            'precio_mensualidad' => 'required|numeric|min:0',
            'precio_mensualidad_recurrente' => 'nullable|numeric|min:0',
            'precio_pago_unico' => 'nullable|numeric|min:0',
            'meses_duracion' => 'required|integer|min:1',
            'meses_cobrables' => 'required|integer|min:0|lte:meses_duracion',
            'meses_gratis' => 'required|integer|min:0',
            'comision_monto' => 'required|numeric|min:0',
            'retencion_monto' => 'required|numeric|min:0',
            'retencion_mes_numero' => 'nullable|integer|min:1|max:12',
            'notas' => 'nullable|string',
        ]);

        $validated['plan_id'] = $plan->id;
        $validated['creado_por'] = auth()->id();
        $validated['estado_publicacion'] = 'borrador';

        $planVersion = PlanVersion::create($validated);

        return redirect()
            //->route('planes-versiones.show', ['plan' => $plan, 'planVersion' => $planVersion])
            ->route('planes.planes-versiones.index', $planVersion->plan)
            ->with('success', 'Versión de plan creada correctamente.');
    }

    public function show(Request $request, PlanVersion $planVersion)
    {
        $planVersion->load(['plan', 'creadoPor', 'actualizadoPor', 'membresias', 'pagos']);

        //$backUrl = route('planes.planes-versiones.index', $planVersion->plan);

        return view('planes-versiones.show', [
            'planVersion' => $planVersion,
            //'backUrl' => $backUrl,
        ]);
    }

    public function edit(Request $request, PlanVersion $planVersion)
    {
        if ($planVersion->tienePagos()) {
            return redirect()
                ->route('planes-versiones.show', $planVersion)
                ->with('error', 'No se puede editar una versión que ya tiene pagos registrados.');
        }

        //$returnUrl = $this->resolveReturnUrl($request, route('planes-versiones.show', $planVersion));

        return view('planes-versiones.edit', [
            'planVersion' => $planVersion,
            //'cancelUrl' => $returnUrl,
        ]);
    }

    public function update(Request $request, PlanVersion $planVersion)
    {
        if ($planVersion->tienePagos()) {
            return redirect()
                ->route('planes-versiones.show', $planVersion)
                ->with('error', 'No se puede editar una versión que ya tiene pagos registrados.');
        }

        $validated = $request->validate([
            'nombre_comercial' => 'required|string|max:150',
            'vigencia_desde' => 'required|date',
            'vigencia_hasta' => 'nullable|date|after:vigencia_desde',
            'precio_inscripcion' => 'required|numeric|min:0',
            'precio_mensualidad' => 'required|numeric|min:0',
            'precio_mensualidad_recurrente' => 'nullable|numeric|min:0',
            'precio_pago_unico' => 'nullable|numeric|min:0',
            'meses_duracion' => 'required|integer|min:1',
            'meses_cobrables' => 'required|integer|min:0|lte:meses_duracion',
            'meses_gratis' => 'required|integer|min:0',
            'comision_monto' => 'required|numeric|min:0',
            'retencion_monto' => 'required|numeric|min:0',
            'retencion_mes_numero' => 'nullable|integer|min:1|max:12',
            'notas' => 'nullable|string',
            'return_url' => 'nullable|string',
        ]);

        /*$returnUrl = $this->resolveReturnUrl(
            $request,
            route('planes-versiones.show', $planVersion),
            $validated['return_url'] ?? null
        );*/

        //unset($validated['return_url']);

        $validated['actualizado_por'] = auth()->id();

        $planVersion->update($validated);

        return redirect()
            //->to($returnUrl)
            ->route('planes-versiones.show', $planVersion)
            ->with('success', 'Versión de plan actualizada correctamente.');
    }

    /*private function resolveReturnUrl(Request $request, string $fallbackUrl, ?string $returnUrl = null): string
    {
        $returnUrl = $returnUrl ?: url()->previous();
        $currentUrl = $request->fullUrl();
        $baseUrl = url('/');

        if (!str_starts_with($returnUrl, $baseUrl) || $returnUrl === $currentUrl) {
            return $fallbackUrl;
        }

        return $returnUrl;
    }*/

    public function publish(Request $request, PlanVersion $planVersion)
    {
        // Validar que no haya traslape de vigencias publicadas
        $traslape = PlanVersion::where('plan_id', $planVersion->plan_id)
            ->where('id', '!=', $planVersion->id)
            ->where('estado_publicacion', 'publicado')
            ->where('vigencia_desde', '<=', $planVersion->vigencia_hasta ?? now()->toDateString())
            ->where(function ($q) use ($planVersion) {
                $q->whereNull('vigencia_hasta')
                  ->orWhere('vigencia_hasta', '>=', $planVersion->vigencia_desde);
            })
            ->exists();

        if ($traslape) {
            return redirect()
                ->route('planes-versiones.show', ['planVersion' => $planVersion])
                ->with('error', 'No se puede publicar esta versión. Existe traslape de vigencias con otras versiones publicadas.');
        }

        $planVersion->update([
            'estado_publicacion' => 'publicado',
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()
            ->route('planes-versiones.show', ['planVersion' => $planVersion])
            ->with('success', 'Versión de plan publicada correctamente.');
    }

    public function closeVersion(Request $request, PlanVersion $planVersion)
    {
        $planVersion->update([
            'vigencia_hasta' => now()->toDateString(),
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()
            ->route('planes-versiones.show', $planVersion)
            ->with('success', 'Versión de plan cerrada correctamente.');
    }

    public function destroy(PlanVersion $planVersion)
    {
        if (!$planVersion->puedeBorrarse()) {
            return redirect()
                ->route('planes.planes-versiones.index', ['plan' => $planVersion->plan])
                ->with('error', 'No se puede eliminar una versión que tiene membresías o pagos asociados.');
        }

        $plan = $planVersion->plan;
        $planVersion->delete();

        return redirect()
            ->route('planes.planes-versiones.index', ['plan' => $plan])
            ->with('success', 'Versión de plan eliminada correctamente.');
    }
}
