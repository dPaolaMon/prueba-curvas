@php
    $editando = isset($maquina) && $maquina->id;
@endphp

<div>
    @csrf
    @if($editando)
        @method('PUT')
    @endif

    {{-- Errores --}}
    @include('_partials.swal-form-errors')

    <div class="row g-3">
        {{-- Nombre --}}
        <div class="col-12">
            <label for="nombre" class="form-label">
                Nombre <span class="text-danger">*</span>
            </label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                class="form-control"
                value="{{ old('nombre', $maquina->nombre ?? '') }}"
                required
            >
            @error('nombre')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="col-12">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea
                id="descripcion"
                name="descripcion"
                rows="5"
                class="form-control"
                placeholder="Descripción de la máquina (opcional)"
            >{{ old('descripcion', $maquina->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="d-flex gap-2 justify-content-end pt-4 border-top mt-4">
        <a href="{{ route('maquinas.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>{{ $editando ? 'Actualizar Máquina' : 'Guardar Máquina' }}
        </button>
    </div>
</div>
