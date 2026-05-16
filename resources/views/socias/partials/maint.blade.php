@php
    $editando = isset($socia) && $socia->id;
    use App\Services\CommonDataService;
    $estadosCiviles = CommonDataService::getCivilStatuses();
    $metodosPago = CommonDataService::getPaymentMethods();
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

    {{-- SECCIÓN: Datos Personales --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">1</span>
            Datos Personales
        </h3>

        <div class="row g-3">
            {{-- Nombre --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="nombre" class="form-label">
                    Nombre <span class="text-danger">*</span>
                </label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                       value="{{ old('nombre', $socia->nombre ?? '') }}"
                       required>
            </div>

            {{-- Apellidos --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="apellidos" class="form-label">
                    Apellidos <span class="text-danger">*</span>
                </label>
                <input type="text" id="apellidos" name="apellidos" class="form-control"
                       value="{{ old('apellidos', $socia->apellidos ?? '') }}"
                       required>
            </div>

            {{-- Email --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="email" class="form-label">
                    Correo Electrónico
                </label>
                <input type="email" id="email" name="email" class="form-control"
                       value="{{ old('email', $socia->email ?? '') }}">
            </div>

            {{-- Celular --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="celular" class="form-label">
                    Celular <span class="text-danger">*</span>
                </label>
                <input type="tel" id="celular" name="celular" class="form-control"
                       value="{{ old('celular', $socia->celular ?? '') }}"
                       required>
            </div>

            {{-- Fecha de Nacimiento --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="fecha_nacimiento" class="form-label">
                    Fecha de Nacimiento <span class="text-danger">*</span>
                </label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control"
                       value="{{ old('fecha_nacimiento', optional($socia->fecha_nacimiento)->format('Y-m-d')) }}"
                       required>
            </div>

            {{-- Estado Civil --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="estado_civil" class="form-label">Estado Civil</label>
                <input type="text" id="estado_civil" name="estado_civil" list="estados_civiles_list" class="form-control"
                       value="{{ old('estado_civil', $socia->estado_civil ?? '') }}"
                       placeholder="Seleccione o escriba un estado civil">
                <datalist id="estados_civiles_list">
                    @foreach($estadosCiviles as $estado)
                        <option value="{{ $estado }}">
                    @endforeach
                </datalist>
            </div>

            {{-- Ocupación --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="ocupacion" class="form-label">Ocupación</label>
                <input type="text" id="ocupacion" name="ocupacion" class="form-control"
                       value="{{ old('ocupacion', $socia->ocupacion ?? '') }}">
            </div>

            {{-- Método de Pago --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="metodo_pago" class="form-label">
                    Método de Pago <span class="text-danger">*</span>
                </label>
                <input type="text" id="metodo_pago" name="metodo_pago" list="metodos_pago_list" class="form-control"
                       value="{{ old('metodo_pago', $socia->metodo_pago ?? '') }}"
                       placeholder="Seleccione o escriba un método de pago"
                       required>
                <datalist id="metodos_pago_list">
                    @foreach($metodosPago as $metodo)
                        <option value="{{ $metodo }}">
                    @endforeach
                </datalist>
            </div>

            {{-- Foto --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*" class="form-control" onchange="previewFoto(this)">
                <div id="fotoPreview" class="mt-3">
                    @if($socia->foto)
                        <img src="{{ asset('storage/' . $socia->foto) }}" alt="Foto actual" class="rounded img-thumbnail" width="80" height="80" style="object-fit: cover;">
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Ubicación --}}
    <div class="mb-5 pb-3 border-bottom">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">2</span>
            Ubicación
        </h3>

        <div class="row g-3">
            {{-- Estado --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="estado_id" class="form-label">
                    Estado <span class="text-danger">*</span>
                </label>
                <select id="estado_id" name="estado_id" class="form-select" onchange="actualizarMunicipios()" required>
                    <option value="">-- Seleccione un estado --</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado->id }}" {{ $editando && $socia->estado_id == $estado->id ? 'selected' : '' }}>{{ $estado->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Municipio --}}
            <div class="col-12 col-md-6 col-lg-4">
                <label for="municipio_id" class="form-label">
                    Municipio <span class="text-danger">*</span>
                </label>
                <select id="municipio_id" name="municipio_id" class="form-select" required>
                    <option value="">-- Seleccione un municipio --</option>
                </select>
            </div>
        </div>

        <div class="row g-3 mt-1">
            {{-- Dirección --}}
            <div class="col-12">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control"
                       value="{{ old('direccion', $socia->direccion ?? '') }}">
            </div>
        </div>

        <div class="row g-3 mt-1">
            {{-- Colonia --}}
            <div class="col-12 col-md-6">
                <label for="colonia" class="form-label">Colonia</label>
                <input type="text" id="colonia" name="colonia" class="form-control"
                       value="{{ old('colonia', $socia->colonia ?? '') }}">
            </div>

            {{-- Código Postal --}}
            <div class="col-12 col-md-6">
                <label for="codigo_postal" class="form-label">Código Postal</label>
                <input type="text" id="codigo_postal" name="codigo_postal" class="form-control"
                       value="{{ old('codigo_postal', $socia->codigo_postal ?? '') }}">
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Información Médica --}}
    <div class="mb-5">
        <h3 class="h5 mb-4">
            <span class="badge bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">3</span>
            Información Médica
        </h3>

        <div class="row g-3">
            {{-- Contacto de Emergencia --}}
            <div class="col-12">
                <label for="contacto_emergencia" class="form-label">Contacto de Emergencia</label>
                <textarea id="contacto_emergencia" name="contacto_emergencia" rows="3" class="form-control"
                          placeholder="Nombre, relación y teléfono">{{ old('contacto_emergencia', $socia->contacto_emergencia ?? '') }}</textarea>
            </div>

            {{-- Lesión / Padecimiento Crónico --}}
            <div class="col-12">
                <label for="padecimiento_cronico" class="form-label">Lesión / Padecimiento Crónico</label>
                <textarea id="padecimiento_cronico" name="padecimiento_cronico" rows="3" class="form-control"
                          placeholder="Describa cualquier lesión o padecimiento crónico">{{ old('padecimiento_cronico', $socia->padecimiento_cronico ?? '') }}</textarea>
            </div>

            {{-- Factor X --}}
            <div class="col-12">
                <label for="factorx" class="form-label">Factor X</label>
                <textarea id="factorx" name="factorx" rows="3" class="form-control"
                          placeholder="Información adicional sobre Factor X">{{ old('factorx', $socia->factorx ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="d-flex gap-2 justify-content-end pt-4 border-top mt-5">
        <a href="{{ route('socias.index') }}" class="btn btn-danger">Cancelar</a>
        <button type="submit" class="btn btn-primary">@if($editando)Actualizar @else Guardar @endif socia</button>
    </div>
</div>

<script>
    const municipios = @json($municipios);

    function previewFoto(input) {
        const preview = document.getElementById('fotoPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="rounded img-thumbnail" width="80" height="80" style="object-fit: cover;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function actualizarMunicipios() {
        const estadoId = document.getElementById('estado_id').value;
        const municipioSelect = document.getElementById('municipio_id');
        const municipioActual = '{{ $socia->municipio_id ?? '' }}';
        
        municipioSelect.innerHTML = '<option value="">-- Seleccione un municipio --</option>';
        
        if (estadoId) {
            const municipiosFiltrados = municipios.filter(m => m.estado_id == estadoId);
            municipiosFiltrados.forEach(m => {
                const option = document.createElement('option');
                option.value = m.id;
                option.text = m.nombre;
                if (municipioActual && m.id == municipioActual) {
                    option.selected = true;
                }
                municipioSelect.appendChild(option);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        actualizarMunicipios();
    });
</script>
