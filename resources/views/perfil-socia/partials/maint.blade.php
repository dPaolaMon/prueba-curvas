@php
    use App\Services\CommonDataService;

    $estadosCiviles = CommonDataService::getCivilStatuses();
@endphp

<div>
    @csrf
    @method('PUT')

    @include('_partials.swal-form-errors')

    <div class="row g-4">
        <div class="col-12 col-lg-3">
            <div class="border rounded p-3 h-100">
                <h6 class="fw-bold mb-3">Foto de perfil</h6>

                <div id="fotoPreview" class="mb-3 text-center">
                    @if($socia->foto)
                        <img src="{{ asset('storage/' . $socia->foto) }}" alt="Foto actual" class="rounded img-thumbnail" width="180" height="180" style="object-fit: cover;">
                    @else
                        <div class="border rounded d-flex align-items-center justify-content-center text-body-secondary mx-auto" style="width: 180px; height: 180px;">
                            Sin foto
                        </div>
                    @endif
                </div>

                <label for="foto" class="form-label">Cambiar foto</label>
                <input type="file" id="foto" name="foto" accept="image/*" class="form-control" onchange="previewFoto(this)">

                <div class="mt-3 small text-body-secondary">
                    Formatos sugeridos: JPG o PNG.
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-9">
            <div class="row g-3">
                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="fw-bold mb-3">Datos personales</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="num_socia" class="form-label">Num. Socia</label>
                                <input type="text" id="num_socia" class="form-control" value="{{ $socia->num_socia }}" disabled>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $socia->nombre) }}" required>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" id="apellidos" name="apellidos" class="form-control" value="{{ old('apellidos', $socia->apellidos) }}" required>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento <span class="text-danger">*</span></label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', optional($socia->fecha_nacimiento)->format('Y-m-d')) }}" required>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="estado_civil" class="form-label">Estado civil</label>
                                <input type="text" id="estado_civil" name="estado_civil" list="estados_civiles_list" class="form-control"
                                    value="{{ old('estado_civil', $socia->estado_civil) }}"
                                    placeholder="Seleccione o escriba un estado civil">
                                <datalist id="estados_civiles_list">
                                    @foreach($estadosCiviles as $estado)
                                        <option value="{{ $estado }}">
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="ocupacion" class="form-label">Ocupación</label>
                                <input type="text" id="ocupacion" name="ocupacion" class="form-control" value="{{ old('ocupacion', $socia->ocupacion) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="fw-bold mb-3">Contacto y domicilio</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                                <input type="text" id="celular" name="celular" class="form-control" value="{{ old('celular', $socia->celular) }}" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $socia->email) }}">
                            </div>

                            <div class="col-12">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion', $socia->direccion) }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="colonia" class="form-label">Colonia</label>
                                <input type="text" id="colonia" name="colonia" class="form-control" value="{{ old('colonia', $socia->colonia) }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="codigo_postal" class="form-label">Código postal</label>
                                <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" value="{{ old('codigo_postal', $socia->codigo_postal) }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="estado_id" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select id="estado_id" name="estado_id" class="form-select" onchange="actualizarMunicipios()" required>
                                    <option value="">-- Seleccione un estado --</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}" {{ (string) old('estado_id', $socia->estado_id) === (string) $estado->id ? 'selected' : '' }}>
                                            {{ $estado->id }} - {{ $estado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="municipio_id" class="form-label">Municipio <span class="text-danger">*</span></label>
                                <select id="municipio_id" name="municipio_id" class="form-select" required>
                                    <option value="">-- Seleccione un municipio --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="fw-bold mb-3">Membresía</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="metodo_pago" class="form-label">Método de pago</label>
                                <input type="text" id="metodo_pago" class="form-control" value="{{ $socia->metodo_pago }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="fw-bold mb-3">Salud y notas</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="contacto_emergencia" class="form-label">Contacto de emergencia</label>
                                <textarea id="contacto_emergencia" name="contacto_emergencia" rows="3" class="form-control">{{ old('contacto_emergencia', $socia->contacto_emergencia) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label for="padecimiento_cronico" class="form-label">Padecimiento crónico</label>
                                <textarea id="padecimiento_cronico" name="padecimiento_cronico" rows="3" class="form-control">{{ old('padecimiento_cronico', $socia->padecimiento_cronico) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label for="comentarios" class="form-label">Comentarios</label>
                                <textarea id="comentarios" class="form-control" rows="3" disabled>{{ $socia->comentarios }}</textarea>
                            </div>

                            <div class="col-12">
                                <label for="factorx" class="form-label">Factor X</label>
                                <textarea id="factorx" class="form-control" rows="3" disabled>{{ $socia->factorx }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end pt-4 border-top mt-4">
        <a href="{{ route('perfil-socia.show') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>Guardar cambios
        </button>
    </div>
</div>

<script>
    const municipios = @json($municipios);

    function previewFoto(input) {
        const preview = document.getElementById('fotoPreview');
        if (!input.files || !input.files[0]) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="rounded img-thumbnail" width="180" height="180" style="object-fit: cover;">';
        };
        reader.readAsDataURL(input.files[0]);
    }

    function actualizarMunicipios() {
        const estadoId = document.getElementById('estado_id').value;
        const municipioSelect = document.getElementById('municipio_id');
        const municipioActual = '{{ old('municipio_id', $socia->municipio_id) }}';

        municipioSelect.innerHTML = '<option value="">-- Seleccione un municipio --</option>';

        if (!estadoId) {
            return;
        }

        const municipiosFiltrados = municipios.filter(municipio => String(municipio.estado_id) === String(estadoId));

        municipiosFiltrados.forEach((municipio) => {
            const option = document.createElement('option');
            option.value = municipio.id;
            option.text = `${municipio.id} - ${municipio.nombre}`;

            if (String(municipio.id) === String(municipioActual)) {
                option.selected = true;
            }

            municipioSelect.appendChild(option);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarMunicipios();
    });
</script>
