@php
    $editando = isset($pago) && $pago->id;
    $pagoDefaults = $pagoDefaults ?? [];
@endphp

<div>
    @csrf
    @if($editando)
        @method('PUT')
    @endif

    {{-- Errores --}}
    @include('_partials.swal-form-errors', ['title' => 'Errores'])

    {{-- SECCIÓN: Membresía --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">1</span>
            Membresía
        </h3>

        @if(!$editando && $membresia)
            <div class="alert alert-info">
                <strong>{{ $membresia->socia->nombre }} {{ $membresia->socia->apellidos }}</strong><br>
                <small>Plan: {{ $membresia->planVersion->plan->nombre }} - {{ $membresia->planVersion->nombre_comercial }}</small><br>
                <small>
                    Método de pago: {{ $membresia->metodo_pago ?: 'Sin definir' }}
                    @if($membresia->ciclo_facturacion)
                        | Ciclo: {{ ucfirst($membresia->ciclo_facturacion) }}
                    @endif
                    @if($membresia->dia_cobro)
                        | Día de cobro: {{ $membresia->dia_cobro }}
                    @endif
                </small>
            </div>
            <input type="hidden" name="membresia_id" value="{{ $membresia->id }}">
        @elseif(!$editando)
            <div class="mb-3">
                <label for="membresia_id" class="form-label">Membresía <span class="text-danger">*</span></label>
                <select id="membresia_id" name="membresia_id" class="form-select" required>
                    <option value="">-- Seleccione una membresía --</option>
                    @forelse(($membresias ?? []) as $itemMembresia)
                        <option
                            value="{{ $itemMembresia->id }}"
                            data-metodo-pago="{{ $itemMembresia->metodo_pago ?? '' }}"
                            data-comision-monto="{{ $itemMembresia->planVersion->comision_monto ?? 0 }}"
                            data-retencion-monto="{{ $itemMembresia->planVersion->retencion_monto ?? 0 }}"
                            @selected((string) old('membresia_id') === (string) $itemMembresia->id)
                        >
                            #{{ $itemMembresia->id }} - {{ $itemMembresia->socia->nombre }} {{ $itemMembresia->socia->apellidos }} | {{ $itemMembresia->planVersion->plan->nombre }} - {{ $itemMembresia->planVersion->nombre_comercial }}
                        </option>
                    @empty
                        <option value="" disabled>Sin membresias disponibles</option>
                    @endforelse
                </select>
            </div>
        @else
            <div class="alert alert-info">
                <strong>{{ $pago->membresia->socia->nombre }} {{ $pago->membresia->socia->apellidos }}</strong><br>
                <small>Plan: {{ $pago->membresia->planVersion->plan->nombre }}</small>
            </div>
        @endif
    </div>

    {{-- SECCIÓN: Tipo de Pago --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-info rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">2</span>
            Tipo de Pago
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="tipo_pago" class="form-label">Tipo <span class="text-danger">*</span></label>
                <select id="tipo_pago" name="tipo_pago" class="form-select" @if($editando) disabled @endif required>
                    <option value="">-- Seleccione --</option>
                    <option value="inscripcion" @selected(old('tipo_pago', $pago->tipo_pago ?? '') === 'inscripcion')>Inscripción</option>
                    <option value="mensualidad" @selected(old('tipo_pago', $pago->tipo_pago ?? '') === 'mensualidad')>Mensualidad</option>
                    <option value="reingreso" @selected(old('tipo_pago', $pago->tipo_pago ?? '') === 'reingreso')>Reingreso</option>
                    <option value="promocion" @selected(old('tipo_pago', $pago->tipo_pago ?? '') === 'promocion')>Promoción</option>
                    <option value="ajuste" @selected(old('tipo_pago', $pago->tipo_pago ?? '') === 'ajuste')>Ajuste</option>
                    <option value="anual" @selected(old('tipo_pago', $pago->tipo_pago ?? '') === 'anual')>Anual</option>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label for="periodo_anio" class="form-label">Año</label>
                <input type="number" id="periodo_anio" name="periodo_anio" class="form-control" min="2020" max="2100"
                       value="{{ old('periodo_anio', $pago->periodo_anio ?? ($pagoDefaults['periodo_anio'] ?? '')) }}">
            </div>

            <div class="col-12 col-md-3">
                <label for="periodo_mes" class="form-label">Mes</label>
                <select id="periodo_mes" name="periodo_mes" class="form-select">
                    <option value="">-- Sin mes --</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" @selected(old('periodo_mes', $pago->periodo_mes ?? ($pagoDefaults['periodo_mes'] ?? '')) == $i)>
                            {{ sprintf('%02d', $i) }} - {{ now()->setMonth($i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Importes --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-success rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">3</span>
            Importes
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label for="monto_lista" class="form-label">Monto Lista $ <span class="text-danger">*</span></label>
                <input type="number" id="monto_lista" name="monto_lista" class="form-control" step="0.01" min="0"
                       value="{{ old('monto_lista', $pago->monto_lista ?? 0) }}" 
                       oninput="calcularMontoFinal()" required>
            </div>

            <div class="col-12 col-md-4">
                <label for="monto_descuento" class="form-label">Descuento $ <span class="text-danger">*</span></label>
                <input type="number" id="monto_descuento" name="monto_descuento" class="form-control" step="0.01" min="0"
                       value="{{ old('monto_descuento', $pago->monto_descuento ?? 0) }}" 
                       oninput="calcularMontoFinal()" required>
            </div>

            <div class="col-12 col-md-4">
                <label for="monto_recargo" class="form-label">Recargo $ <span class="text-danger">*</span></label>
                <input type="number" id="monto_recargo" name="monto_recargo" class="form-control" step="0.01" min="0"
                       value="{{ old('monto_recargo', $pago->monto_recargo ?? 0) }}" 
                       oninput="calcularMontoFinal()" required>
            </div>

            <div class="col-12">
                <div class="alert alert-info">
                    <small>
                        <strong>Monto Final:</strong> $<span id="monto_final_display">0.00</span>
                        <input type="hidden" id="monto_final" name="monto_final" value="{{ old('monto_final', $pago->monto_final ?? 0) }}">
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Método de Pago --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-warning rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">4</span>
            Método de Pago
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="metodo_pago" class="form-label">Método <span class="text-danger">*</span></label>
                <input type="text" id="metodo_pago" name="metodo_pago" list="metodos_pago_list" class="form-control"
                       value="{{ old('metodo_pago', $pago->metodo_pago ?? ($pagoDefaults['metodo_pago'] ?? '')) }}" required>
                <datalist id="metodos_pago_list">
                    @foreach($metodosPago as $metodo)
                        <option value="{{ $metodo }}">
                    @endforeach
                </datalist>
            </div>

            <div class="col-12 col-md-6">
                <label for="referencia_externa" class="form-label">Referencia (Transacción/Cheque)</label>
                <input type="text" id="referencia_externa" name="referencia_externa" class="form-control"
                       value="{{ old('referencia_externa', $pago->referencia_externa ?? '') }}"
                       placeholder="Ej: TRX123456, Cheque #789">
            </div>

            <div class="col-12 col-md-6">
                <label for="fecha_programada" class="form-label">Fecha Programada</label>
                <input type="date" id="fecha_programada" name="fecha_programada" class="form-control"
                      value="{{ old('fecha_programada', optional($pago->fecha_programada)->format('Y-m-d') ?? ($pagoDefaults['fecha_programada'] ?? '')) }}">
            </div>

            <div class="col-12 col-md-6">
                <label for="fecha_pago" class="form-label">Fecha de Pago <span class="text-danger">*</span></label>
                <input type="datetime-local" id="fecha_pago" name="fecha_pago" class="form-control"
                       value="{{ old('fecha_pago', optional($pago->fecha_pago)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\T00:00')) }}" required>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Comisión y Retención --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-danger rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">5</span>
            Comisión y Retención
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label for="comision_monto" class="form-label">Comisión $ <span class="text-danger">*</span></label>
                <input type="number" id="comision_monto" name="comision_monto" class="form-control" step="0.01" min="0"
                      value="{{ old('comision_monto', $pago->comision_monto ?? ($pagoDefaults['comision_monto'] ?? 0)) }}" required readonly>
                <small class="text-muted">Se calcula automáticamente desde el plan</small>
            </div>

            <div class="col-12 col-md-4">
                <label for="comision_pagable_en" class="form-label">Comisión Pagable En</label>
                <input type="date" id="comision_pagable_en" name="comision_pagable_en" class="form-control"
                       value="{{ old('comision_pagable_en', optional($pago->comision_pagable_en)->format('Y-m-d')) }}">
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="alert alert-secondary mb-0" style="font-size: 0.875rem;">
                    <i class="bi bi-info-circle me-1"></i>Política comercial del plan
                </div>
            </div>

            <div class="col-12 col-md-4">
                <label for="retencion_monto" class="form-label">Retención $ <span class="text-danger">*</span></label>
                <input type="number" id="retencion_monto" name="retencion_monto" class="form-control" step="0.01" min="0"
                       value="{{ old('retencion_monto', $pago->retencion_monto ?? ($pagoDefaults['retencion_monto'] ?? 0)) }}" required readonly>
                <small class="text-muted">Se calcula automáticamente</small>
            </div>

            <div class="col-12 col-md-4">
                <label for="retencion_aplica" class="form-label">¿Aplica Retención?</label>
                <div class="form-check mt-2">
                    <input type="hidden" name="retencion_aplica" value="0">
                    <input type="checkbox" id="retencion_aplica" name="retencion_aplica" class="form-check-input" value="1"
                           @checked(old('retencion_aplica', $pago->retencion_aplica ?? ($pagoDefaults['retencion_aplica'] ?? false)))>
                    <label class="form-check-label" for="retencion_aplica">
                        Sí, aplica retención
                    </label>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <label for="retencion_liberable_en" class="form-label">Liberable En</label>
                <input type="date" id="retencion_liberable_en" name="retencion_liberable_en" class="form-control"
                       value="{{ old('retencion_liberable_en', optional($pago->retencion_liberable_en)->format('Y-m-d')) }}">
            </div>
        </div>
    </div>

    {{-- Botones --}}
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ $cancelUrl ?? route('pagos.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>{{ $editando ? 'Actualizar' : 'Registrar' }} Pago
        </button>
    </div>
</div>

@push('scripts')
<script>
    const hasOldInput = @json(count(session()->getOldInput() ?? []) > 0);

    function calcularMontoFinal() {
        const lista = parseFloat(document.getElementById('monto_lista').value) || 0;
        const descuento = parseFloat(document.getElementById('monto_descuento').value) || 0;
        const recargo = parseFloat(document.getElementById('monto_recargo').value) || 0;
        const final = lista - descuento + recargo;
        
        document.getElementById('monto_final').value = final.toFixed(2);
        document.getElementById('monto_final_display').textContent = final.toFixed(2);
    }

    function autocompletarDesdeMembresia(forzar = false) {
        const selectMembresia = document.getElementById('membresia_id');
        if (!selectMembresia) {
            return;
        }

        const selectedOption = selectMembresia.options[selectMembresia.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            return;
        }

        const metodoPagoInput = document.getElementById('metodo_pago');
        const comisionInput = document.getElementById('comision_monto');
        const retencionInput = document.getElementById('retencion_monto');
        const retencionAplicaInput = document.getElementById('retencion_aplica');

        const metodoPago = selectedOption.dataset.metodoPago || '';
        const comisionMonto = Number.parseFloat(selectedOption.dataset.comisionMonto || '0') || 0;
        const retencionMonto = Number.parseFloat(selectedOption.dataset.retencionMonto || '0') || 0;

        if (metodoPagoInput && (forzar || metodoPagoInput.value.trim() === '')) {
            metodoPagoInput.value = metodoPago;
        }

        if (comisionInput && (forzar || Number.parseFloat(comisionInput.value || '0') === 0)) {
            comisionInput.value = comisionMonto.toFixed(2);
        }

        if (retencionInput && (forzar || Number.parseFloat(retencionInput.value || '0') === 0)) {
            retencionInput.value = retencionMonto.toFixed(2);
        }

        if (retencionAplicaInput && (forzar || !hasOldInput)) {
            retencionAplicaInput.checked = retencionMonto > 0;
        }
    }

    // Inicializar cálculo al cargar
    calcularMontoFinal();

    document.addEventListener('DOMContentLoaded', function () {
        const selectMembresia = document.getElementById('membresia_id');
        if (!selectMembresia) {
            return;
        }

        selectMembresia.addEventListener('change', function () {
            autocompletarDesdeMembresia(true);
        });

        if (!hasOldInput) {
            autocompletarDesdeMembresia(true);
        }
    });
</script>
@endpush
