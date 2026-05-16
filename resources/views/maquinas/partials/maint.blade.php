@php
    $editando = isset($maquina) && $maquina->id;
@endphp

<div>
    @csrf
    @if($editando)
        @method('PUT')
    @endif

    {{-- Errores --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading">Errores en el formulario:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

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
            Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            {{ $editando ? 'Actualizar Máquina' : 'Guardar Máquina' }}
        </button>
    </div>
</div>
