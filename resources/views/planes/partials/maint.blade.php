@php
    $editando = isset($plan) && $plan->id;
@endphp

<div>
    @csrf
    @if($editando)
        @method('PUT')
    @endif

    {{-- Errores --}}
    @include('_partials.swal-form-errors')

    {{-- SECCIÓN: Información Básica --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">1</span>
            Información Básica del Plan
        </h3>

        <div class="row g-3">
            {{-- Nombre --}}
            <div class="col-12 col-md-6">
                <label for="nombre" class="form-label">
                    Nombre <span class="text-danger">*</span>
                </label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                       value="{{ old('nombre', $plan->nombre ?? '') }}"
                       placeholder="Ej: Plan Normal, Plan Anual"
                       required>
            </div>

            {{-- Estatus --}}
            <div class="col-12 col-md-6">
                <label for="estatus" class="form-label">
                    Estatus <span class="text-danger">*</span>
                </label>
                <select id="estatus" name="estatus" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    <option value="activo" @selected(old('estatus', $plan->estatus ?? '') === 'activo')>Activo</option>
                    <option value="inactivo" @selected(old('estatus', $plan->estatus ?? '') === 'inactivo')>Inactivo</option>
                </select>
            </div>

            {{-- Descripción --}}
            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                          placeholder="Detalles del plan...">{{ old('descripcion', $plan->descripcion ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ $cancelUrl ?? route('planes.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>{{ $editando ? 'Actualizar' : 'Crear' }} Plan
        </button>
    </div>
</div>
