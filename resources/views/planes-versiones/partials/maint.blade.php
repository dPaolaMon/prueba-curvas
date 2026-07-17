@php
    $editando = isset($planVersion) && $planVersion->id;
@endphp

<div>
    @csrf
    @if($editando)
        @method('PUT')
    @endif

    {{-- Errores --}}
    @include('_partials.swal-form-errors', ['title' => 'Corrige los campos marcados'])

    {{-- SECCIÓN: Información Básica --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">1</span>
            Información Básica
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="nombre_comercial" class="form-label">Nombre Comercial <span class="text-danger">*</span></label>
                <input type="text" id="nombre_comercial" name="nombre_comercial" class="form-control"
                       value="{{ old('nombre_comercial', $planVersion->nombre_comercial ?? '') }}"
                       placeholder="Ej: Normal 2026, Plan Anual Jul-2026" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="vigencia_desde" class="form-label">Vigencia Desde <span class="text-danger">*</span></label>
                <input type="date" id="vigencia_desde" name="vigencia_desde" class="form-control"
                       value="{{ old('vigencia_desde', optional($planVersion->vigencia_desde)->format('Y-m-d')) }}" required>
            </div>

            <div class="col-12 col-md-6">
                <label for="vigencia_hasta" class="form-label">Vigencia Hasta</label>
                <input type="date" id="vigencia_hasta" name="vigencia_hasta" class="form-control"
                       value="{{ old('vigencia_hasta', optional($planVersion->vigencia_hasta)->format('Y-m-d')) }}">
                <small class="text-muted">Dejar vacío si no tiene fecha de fin</small>
            </div>

            <div class="col-12 col-md-6">
                <label for="notas" class="form-label">Notas</label>
                <textarea id="notas" name="notas" class="form-control" rows="2">{{ old('notas', $planVersion->notas ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Precios --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">2</span>
            Estructura de Precios
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label for="precio_inscripcion" class="form-label">Inscripción $ <span class="text-danger">*</span></label>
                <input type="number" id="precio_inscripcion" name="precio_inscripcion" class="form-control" step="0.01" min="0"
                       value="{{ old('precio_inscripcion', $planVersion->precio_inscripcion ?? 0) }}" required>
            </div>

            <div class="col-12 col-md-4">
                <label for="precio_mensualidad" class="form-label">Mensualidad $ <span class="text-danger">*</span></label>
                <input type="number" id="precio_mensualidad" name="precio_mensualidad" class="form-control" step="0.01" min="0"
                       value="{{ old('precio_mensualidad', $planVersion->precio_mensualidad ?? 0) }}" required>
            </div>

            <div class="col-12 col-md-4">
                <label for="precio_mensualidad_recurrente" class="form-label">Mensualidad Recurrente $</label>
                <input type="number" id="precio_mensualidad_recurrente" name="precio_mensualidad_recurrente" class="form-control" step="0.01" min="0"
                       value="{{ old('precio_mensualidad_recurrente', $planVersion->precio_mensualidad_recurrente ?? '') }}">
                <small class="text-muted">Si aplica renovación</small>
            </div>

            <div class="col-12 col-md-6">
                <label for="precio_pago_unico" class="form-label">Pago Único $</label>
                <input type="number" id="precio_pago_unico" name="precio_pago_unico" class="form-control" step="0.01" min="0"
                       value="{{ old('precio_pago_unico', $planVersion->precio_pago_unico ?? '') }}">
                <small class="text-muted">Precio si paga todo de una vez</small>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">&nbsp;</label>
                <div class="rounded border bg-body-tertiary p-2 mb-0 h-100 d-flex align-items-center">
                    <small><i class="bi bi-info-circle me-2"></i>Los precios no pueden editarse si ya existen pagos registrados.</small>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Duración y Cobranza --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">3</span>
            Duración y Cobranza
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-3">
                <label for="meses_duracion" class="form-label">Duración Total (meses) <span class="text-danger">*</span></label>
                <input type="number" id="meses_duracion" name="meses_duracion" class="form-control" min="1" max="24"
                       value="{{ old('meses_duracion', $planVersion->meses_duracion ?? 1) }}" required>
            </div>

            <div class="col-12 col-md-3">
                <label for="meses_cobrables" class="form-label">Meses Cobrables <span class="text-danger">*</span></label>
                <input type="number" id="meses_cobrables" name="meses_cobrables" class="form-control" min="0" max="24"
                       value="{{ old('meses_cobrables', $planVersion->meses_cobrables ?? 0) }}" required>
            </div>

            <div class="col-12 col-md-3">
                <label for="meses_gratis" class="form-label">Meses Gratis <span class="text-danger">*</span></label>
                <input type="number" id="meses_gratis" name="meses_gratis" class="form-control" min="0" max="24"
                       value="{{ old('meses_gratis', $planVersion->meses_gratis ?? 0) }}" required>
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="rounded border bg-warning-subtle text-warning-emphasis border-warning p-2 mb-0" style="font-size: 0.875rem;">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <span id="validacion-meses">Validar sumatoria</span>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Comisiones y Retenciones --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">4</span>
            Comisiones y Retenciones
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="comision_monto" class="form-label">Monto de Comisión $ <span class="text-danger">*</span></label>
                <input type="number" id="comision_monto" name="comision_monto" class="form-control" step="0.01" min="0"
                       value="{{ old('comision_monto', $planVersion->comision_monto ?? 0) }}" required>
                <small class="text-muted">Comisión por venta</small>
            </div>

            <div class="col-12 col-md-6">
                <label for="retencion_monto" class="form-label">Monto de Retención $ <span class="text-danger">*</span></label>
                <input type="number" id="retencion_monto" name="retencion_monto" class="form-control" step="0.01" min="0"
                       value="{{ old('retencion_monto', $planVersion->retencion_monto ?? 0) }}" required>
                <small class="text-muted">Retención a cobrar</small>
            </div>

            <div class="col-12 col-md-6">
                <label for="retencion_mes_numero" class="form-label">Mes de Retención</label>
                <select id="retencion_mes_numero" name="retencion_mes_numero" class="form-select">
                    <option value="">-- No aplica --</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" @selected(old('retencion_mes_numero', $planVersion->retencion_mes_numero ?? '') == $i)>
                            Mes {{ $i }}
                        </option>
                    @endfor
                </select>
                <small class="text-muted">En qué mes del plan se retiene</small>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">&nbsp;</label>
                <div class="rounded border bg-body-tertiary p-2 mb-0" style="font-size: 0.875rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    Gestiona las políticas financieras del plan
                </div>
            </div>
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ $editando ? old('return_url', $cancelUrl ?? route('planes-versiones.show', $planVersion)) : ($cancelUrl ?? route('planes.planes-versiones.index', $plan)) }}" class="btn btn-danger">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>{{ $editando ? 'Actualizar' : 'Crear' }} Versión
        </button>
    </div>
</div>

@push('scripts')
<script>
    // Validación de meses
    function validarMeses() {
        const duracion = parseInt(document.getElementById('meses_duracion').value) || 0;
        const cobrables = parseInt(document.getElementById('meses_cobrables').value) || 0;
        const gratis = parseInt(document.getElementById('meses_gratis').value) || 0;
        const total = cobrables + gratis;
        const validacion = document.getElementById('validacion-meses');
        const panel = validacion.parentElement;

        panel.classList.remove(
            'bg-warning-subtle',
            'text-warning-emphasis',
            'border-warning',
            'bg-danger-subtle',
            'text-danger-emphasis',
            'border-danger',
            'bg-success-subtle',
            'text-success-emphasis',
            'border-success'
        );

        if (total > duracion) {
            validacion.textContent = `Total ${total} > Duracion ${duracion}`;
            panel.classList.add('bg-danger-subtle', 'text-danger-emphasis', 'border-danger');
        } else if (total <= duracion) {
            validacion.textContent = `Total ${total} <= Duracion ${duracion}`;
            panel.classList.add('bg-success-subtle', 'text-success-emphasis', 'border-success');
        } else {
            validacion.textContent = 'Validar sumatoria';
            panel.classList.add('bg-warning-subtle', 'text-warning-emphasis', 'border-warning');
        }
    }

    document.getElementById('meses_duracion').addEventListener('change', validarMeses);
    document.getElementById('meses_cobrables').addEventListener('change', validarMeses);
    document.getElementById('meses_gratis').addEventListener('change', validarMeses);
    validarMeses();
</script>
@endpush
