@php
    $editando = isset($membresia) && $membresia->id;
    $sociaSeleccionada = old('socia_id', $sociaSeleccionadaId ?? $membresia->socia_id ?? '');
@endphp

<div>
    @csrf
    @if($editando)
        @method('PUT')
    @endif

    {{-- Errores --}}
    @include('_partials.swal-form-errors', ['title' => 'Errores'])

    {{-- SECCIÓN: Socia y Plan --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">1</span>
            Socia y Plan
        </h3>

        <div class="row g-3">
            @if(!$editando)
                <div class="col-12 col-md-6">
                    <label for="socia_id" class="form-label">Socia <span class="text-danger">*</span></label>
                    <select id="socia_id" name="socia_id" class="form-select" required>
                        <option value="">-- Seleccione una socia --</option>
                        @foreach($socias as $socia)
                            <option value="{{ $socia->id }}" @selected((string) $sociaSeleccionada === (string) $socia->id)>
                                {{ $socia->nombre }} {{ $socia->apellidos }} (#{{ $socia->num_socia }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label for="plan_version_id" class="form-label">Plan <span class="text-danger">*</span></label>
                    <select id="plan_version_id" name="plan_version_id" class="form-select" required>
                        <option value="">-- Seleccione un plan --</option>
                        @foreach($planesVersiones as $pv)
                            <option value="{{ $pv->id }}" @selected(old('plan_version_id') == $pv->id)>
                                {{ $pv->plan->nombre }} - {{ $pv->nombre_comercial }} ({{ $pv->vigencia_desde->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="col-12">
                    <div class="alert alert-info">
                        <small>
                            <strong>Socia:</strong> {{ $membresia->socia->nombre }} {{ $membresia->socia->apellidos }}<br>
                            <strong>Plan:</strong> {{ $membresia->planVersion->plan->nombre }} - {{ $membresia->planVersion->nombre_comercial }}
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SECCIÓN: Fechas --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">2</span>
            Fechas
        </h3>

        <div class="row g-3">
            @if(!$editando)
                <div class="col-12 col-md-4">
                    <label for="fecha_inicio" class="form-label">Inicio <span class="text-danger">*</span></label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
                           value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-12 col-md-4">
                    <label for="fecha_fin_programada" class="form-label">Fin Programada <span class="text-danger">*</span></label>
                    <input type="date" id="fecha_fin_programada" name="fecha_fin_programada" class="form-control"
                           value="{{ old('fecha_fin_programada') }}" required>
                </div>

                <div class="col-12 col-md-4">
                    <label for="fecha_renovacion" class="form-label">Renovación</label>
                    <input type="date" id="fecha_renovacion" name="fecha_renovacion" class="form-control"
                           value="{{ old('fecha_renovacion') }}">
                </div>
            @else
                <div class="col-12 col-md-4">
                    <label class="form-label">Inicio</label>
                    <input type="text" class="form-control" value="{{ $membresia->fecha_inicio->format('d/m/Y') }}" readonly>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Fin Programada</label>
                    <input type="text" class="form-control" value="{{ $membresia->fecha_fin_programada->format('d/m/Y') }}" readonly>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Renovación</label>
                    <input type="text" class="form-control" value="{{ $membresia->fecha_renovacion?->format('d/m/Y') ?? 'N/A' }}" readonly>
                </div>
            @endif
        </div>
    </div>

    {{-- SECCIÓN: Pago --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">3</span>
            Método de Pago
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="metodo_pago" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                <input type="text" id="metodo_pago" name="metodo_pago" list="metodos_pago_list" class="form-control"
                       value="{{ old('metodo_pago', $membresia->metodo_pago ?? '') }}"
                       placeholder="Seleccione o escriba" required>
                <datalist id="metodos_pago_list">
                    @foreach($metodosPago as $metodo)
                        <option value="{{ $metodo }}">
                    @endforeach
                </datalist>
            </div>

            <div class="col-12 col-md-6">
                <label for="dia_cobro" class="form-label">Día de Cobro</label>
                <select id="dia_cobro" name="dia_cobro" class="form-select">
                    <option value="">-- Sin día específico --</option>
                    @for($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" @selected(old('dia_cobro', $membresia->dia_cobro ?? '') == $i)>
                            Día {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="ciclo_facturacion" class="form-label">Ciclo de Facturación <span class="text-danger">*</span></label>
                <select id="ciclo_facturacion" name="ciclo_facturacion" class="form-select" required>
                    <option value="mensual" @selected(old('ciclo_facturacion', $membresia->ciclo_facturacion ?? 'mensual') === 'mensual')>Mensual</option>
                    <option value="bimestral" @selected(old('ciclo_facturacion', $membresia->ciclo_facturacion ?? '') === 'bimestral')>Bimestral</option>
                    <option value="trimestral" @selected(old('ciclo_facturacion', $membresia->ciclo_facturacion ?? '') === 'trimestral')>Trimestral</option>
                    <option value="semestral" @selected(old('ciclo_facturacion', $membresia->ciclo_facturacion ?? '') === 'semestral')>Semestral</option>
                    <option value="anual" @selected(old('ciclo_facturacion', $membresia->ciclo_facturacion ?? '') === 'anual')>Anual</option>
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="periodo_gracia_dias" class="form-label">Período de Gracia (días) <span class="text-danger">*</span></label>
                <input type="number" id="periodo_gracia_dias" name="periodo_gracia_dias" class="form-control" min="0" max="90"
                       value="{{ old('periodo_gracia_dias', $membresia->periodo_gracia_dias ?? 0) }}" required>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Detalles Adicionales --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">4</span>
            Detalles Adicionales
        </h3>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="vendedor_user_id" class="form-label">Vendedor</label>
                <input type="text" class="form-control" value="{{ $membresia->vendedor?->name ?? 'No asignado' }}" readonly>
            </div>

            @if($editando)
                <div class="col-12 col-md-6">
                    <label for="estatus" class="form-label">Estatus <span class="text-danger">*</span></label>
                    <select id="estatus" name="estatus" class="form-select" required>
                        @foreach($estatusOpciones as $opcion)
                            <option value="{{ $opcion['value'] }}" @selected(old('estatus', strtolower((string) $membresia->estatus)) === $opcion['value'])>
                                {{ $opcion['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-12 col-md-6">
                <label for="motivo_baja" class="form-label">Motivo de Baja</label>
                <input type="text" id="motivo_baja" name="motivo_baja" class="form-control"
                       value="{{ old('motivo_baja', $membresia->motivo_baja ?? '') }}"
                       placeholder="Motivo si aplica">
            </div>

            <div class="col-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea id="observaciones" name="observaciones" class="form-control" rows="3">{{ old('observaciones', $membresia->observaciones ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Botones --}}
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ $cancelUrl ?? route('membresias.index') }}" class="btn btn-danger">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>{{ $editando ? 'Actualizar' : 'Crear' }} Membresía
        </button>
    </div>
</div>
