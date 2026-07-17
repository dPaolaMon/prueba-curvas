@php
    $editando = isset($ejercicio) && $ejercicio->id;
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
                value="{{ old('nombre', $ejercicio->nombre ?? '') }}"
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
                rows="4"
                class="form-control"
                placeholder="Descripción del ejercicio (opcional)"
            >{{ old('descripcion', $ejercicio->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Color --}}
        <div class="col-12 col-md-8">
            <label for="color" class="form-label">
                Color <span class="text-danger">*</span>
            </label>
            <div class="d-flex gap-3 align-items-center">
                <input
                    type="color"
                    id="color"
                    name="color"
                    class="form-control form-control-color"
                    value="{{ old('color', $ejercicio->color ?? '#E91E63') }}"
                    required
                >
                <input
                    type="text"
                    id="colorText"
                    class="form-control"
                    value="{{ old('color', $ejercicio->color ?? '#E91E63') }}"
                    readonly
                    placeholder="Color seleccionado"
                >
            </div>
            @error('color')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="d-flex gap-2 justify-content-end pt-4 border-top mt-4">
        <a href="{{ route('ejercicios.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>{{ $editando ? 'Actualizar Ejercicio' : 'Guardar Ejercicio' }}
        </button>
    </div>
</div>

<script>
    const colorPicker = document.getElementById('color');
    const colorText = document.getElementById('colorText');

    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value;
        });
    }
</script>
