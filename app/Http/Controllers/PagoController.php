<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Membresia;
use App\Models\Socia;
use App\Services\CommonDataService;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $estatus = $request->query('estatus', 'todos');

        $query = Pago::with(['membresia.socia', 'socia', 'planVersion', 'registradoPor']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('folio_pago', 'like', "%{$search}%")
                  ->orWhereHas('socia', function ($sq) use ($search) {
                      $sq->where('nombre', 'like', "%{$search}%")
                         ->orWhere('apellidos', 'like', "%{$search}%");
                  });
            });
        }

        if ($estatus !== 'todos') {
            $query->where('estatus', $estatus);
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->paginate(10)->withQueryString();

        return view('pagos.index', [
            'pagos' => $pagos,
            'search' => $search,
            'estatus' => $estatus,
        ]);
    }

    public function create(Request $request)
    {
        $membresia_id = $request->query('membresia_id');
        $membresia = null;
        $membresias = Membresia::with(['socia', 'planVersion.plan'])
            ->orderByDesc('id')
            ->get();

        if ($membresia_id) {
            $membresia = Membresia::with(['socia', 'planVersion.plan'])->find($membresia_id);
        }

        $metodosPago = CommonDataService::getPaymentMethods();

        return view('pagos.create', [
            'pago' => new Pago(),
            'membresia' => $membresia,
            'membresias' => $membresias,
            'pagoDefaults' => $this->buildPagoDefaults($membresia),
            'metodosPago' => $metodosPago,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'membresia_id' => 'required|exists:membresias,id',
            'tipo_pago' => 'required|in:inscripcion,mensualidad,reingreso,promocion,ajuste,anual',
            'periodo_anio' => 'nullable|integer|min:2000|max:2100',
            'periodo_mes' => 'nullable|integer|min:1|max:12',
            'fecha_programada' => 'nullable|date',
            'fecha_pago' => 'required|date_format:Y-m-d\TH:i',
            'monto_lista' => 'required|numeric|min:0',
            'monto_descuento' => 'required|numeric|min:0',
            'monto_recargo' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:30',
            'referencia_externa' => 'nullable|string|max:100',
            'comision_monto' => 'required|numeric|min:0',
            'comision_pagable_en' => 'nullable|date',
            'retencion_monto' => 'required|numeric|min:0',
            'retencion_aplica' => 'required|boolean',
            'retencion_liberable_en' => 'nullable|date',
        ]);

        // Obtener membresía y validar
        $membresia = Membresia::findOrFail($validated['membresia_id']);

        $validated['socia_id'] = $membresia->socia_id;
        $validated['plan_version_id'] = $membresia->plan_version_id;
        $validated['folio_pago'] = Pago::first()?->generarFolio() ?? 'PAG-' . now()->format('Y-m-d') . '-001';
        $validated['registrado_por'] = auth()->id();
        $validated['estatus'] = 'aplicado';

        // Calcular monto_final
        $validated['monto_final'] = $validated['monto_lista'] - $validated['monto_descuento'] + $validated['monto_recargo'];

        $pago = Pago::create($validated);

        // Generar snapshot
        $pago->generarSnapshot();
        $pago->save();

        return redirect()
            ->route('pagos.show', $pago)
            ->with('success', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago)
    {
        $pago->load(['membresia.socia', 'socia', 'planVersion', 'registradoPor', 'anuladoPor']);

        return view('pagos.show', [
            'pago' => $pago,
        ]);
    }

    public function edit(Request $request, Pago $pago)
    {
        if ($pago->estatus !== 'pendiente') {
            return redirect()
                ->route('pagos.show', $pago)
                ->with('error', 'Solo se pueden editar pagos pendientes.');
        }

        $metodosPago = CommonDataService::getPaymentMethods();
        $returnUrl = $this->resolveReturnUrl($request, route('pagos.show', $pago));

        return view('pagos.edit', [
            'pago' => $pago,
            'metodosPago' => $metodosPago,
            'cancelUrl' => $returnUrl,
        ]);
    }

    public function update(Request $request, Pago $pago)
    {
        if ($pago->estatus !== 'pendiente') {
            return redirect()
                ->route('pagos.show', $pago)
                ->with('error', 'Solo se pueden editar pagos pendientes.');
        }

        $validated = $request->validate([
            'fecha_pago' => 'required|date_format:Y-m-d\TH:i',
            'monto_lista' => 'required|numeric|min:0',
            'monto_descuento' => 'required|numeric|min:0',
            'monto_recargo' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:30',
            'referencia_externa' => 'nullable|string|max:100',
            'return_url' => 'nullable|string',
        ]);

        $returnUrl = $this->resolveReturnUrl(
            $request,
            route('pagos.show', $pago),
            $validated['return_url'] ?? null
        );

        unset($validated['return_url']);

        $validated['actualizado_por'] = auth()->id();
        $validated['monto_final'] = $validated['monto_lista'] - $validated['monto_descuento'] + $validated['monto_recargo'];

        $pago->update($validated);

        return redirect()
            ->to($returnUrl)
            ->with('success', 'Pago actualizado correctamente.');
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

    public function cancel(Request $request, Pago $pago)
    {
        if (!$pago->esAnulable()) {
            return redirect()
                ->route('pagos.show', $pago)
                ->with('error', 'Este pago no puede ser anulado.');
        }

        $validated = $request->validate([
            'motivo_anulacion' => 'required|string|max:200',
        ]);

        $pago->update([
            'estatus' => 'anulado',
            'anulado_por' => auth()->id(),
            'anulado_at' => now(),
            'motivo_anulacion' => $validated['motivo_anulacion'],
        ]);

        return redirect()
            ->route('pagos.show', $pago)
            ->with('success', 'Pago anulado correctamente.');
    }

    public function reembolso(Request $request, Pago $pago)
    {
        if ($pago->estatus !== 'aplicado') {
            return redirect()
                ->route('pagos.show', $pago)
                ->with('error', 'Solo se pueden reembolsar pagos aplicados.');
        }

        $validated = $request->validate([
            'motivo_anulacion' => 'required|string|max:200',
        ]);

        $pago->update([
            'estatus' => 'reembolsado',
            'anulado_por' => auth()->id(),
            'anulado_at' => now(),
            'motivo_anulacion' => $validated['motivo_anulacion'],
        ]);

        return redirect()
            ->route('pagos.show', $pago)
            ->with('success', 'Pago reembolsado correctamente.');
    }

    public function destroy(Request $request, Pago $pago)
    {
        if ($pago->estatus !== 'pendiente') {
            return redirect()
                ->route('pagos.index')
                ->with('error', 'Solo se pueden eliminar pagos pendientes.');
        }

        $pago->delete();

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago eliminado correctamente.');
    }

    private function buildPagoDefaults(?Membresia $membresia): array
    {
        if (!$membresia || !$membresia->planVersion) {
            return [];
        }

        $fechaProgramada = $this->resolveFechaProgramada($membresia);

        return [
            'metodo_pago' => $membresia->metodo_pago,
            'periodo_anio' => $fechaProgramada?->year ?? now()->year,
            'periodo_mes' => $fechaProgramada?->month ?? now()->month,
            'fecha_programada' => $fechaProgramada?->toDateString(),
            'comision_monto' => (string) $membresia->planVersion->comision_monto,
            'retencion_monto' => (string) $membresia->planVersion->retencion_monto,
            'retencion_aplica' => (float) $membresia->planVersion->retencion_monto > 0,
        ];
    }

    private function resolveFechaProgramada(Membresia $membresia): ?\Illuminate\Support\Carbon
    {
        if (!$membresia->dia_cobro) {
            return null;
        }

        $hoy = now()->startOfDay();
        $fechaProgramada = $hoy->copy()->day(min((int) $membresia->dia_cobro, $hoy->daysInMonth));

        if ($fechaProgramada->lt($hoy)) {
            $siguienteMes = $hoy->copy()->addMonthNoOverflow()->startOfMonth();
            $fechaProgramada = $siguienteMes->copy()->day(min((int) $membresia->dia_cobro, $siguienteMes->daysInMonth));
        }

        return $fechaProgramada;
    }
}
