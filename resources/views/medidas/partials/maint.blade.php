@php
    $editando = isset($medida) && $medida->id;
@endphp

<div>
    @csrf
    <input type="hidden" name="return_to" value="{{ $returnTo ?? route('medidas.index') }}">
    @if($editando)
        @method('PUT')
    @endif

    @include('_partials.swal-form-errors')

    <div class="row g-3">
        <div class="col-12">
            <label for="socia_id" class="form-label">
                Socia <span class="text-danger">*</span>
            </label>
            <select id="socia_id" name="socia_id" class="form-select" required>
                <option value="">Seleccione una socia</option>
                @foreach($socias as $socia)
                    <option value="{{ $socia->id }}" @selected((string) old('socia_id', $medida->socia_id ?? '') === (string) $socia->id)>
                        {{ $socia->nombre }} {{ $socia->apellidos }}
                    </option>
                @endforeach
            </select>
            @error('socia_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="fecha_registro" class="form-label">Fecha de registro <span class="text-danger">*</span></label>
            <input
                type="datetime-local"
                id="fecha_registro"
                name="fecha_registro"
                class="form-control"
                value="{{ old('fecha_registro', isset($medida->fecha_registro) ? $medida->fecha_registro->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                required
            >
            @error('fecha_registro')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="busto" class="form-label">Busto <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="busto" name="busto" class="form-control" value="{{ old('busto', $medida->busto ?? '') }}" required>
            @error('busto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="cintura" class="form-label">Cintura <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="cintura" name="cintura" class="form-control" value="{{ old('cintura', $medida->cintura ?? '') }}" required>
            @error('cintura')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="abdomen" class="form-label">Abdomen <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="abdomen" name="abdomen" class="form-control" value="{{ old('abdomen', $medida->abdomen ?? '') }}" required>
            @error('abdomen')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="caderas" class="form-label">Caderas <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="caderas" name="caderas" class="form-control" value="{{ old('caderas', $medida->caderas ?? '') }}" required>
            @error('caderas')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="muslo" class="form-label">Muslo <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="muslo" name="muslo" class="form-control" value="{{ old('muslo', $medida->muslo ?? '') }}" required>
            @error('muslo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="brazo" class="form-label">Brazo <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="brazo" name="brazo" class="form-control" value="{{ old('brazo', $medida->brazo ?? '') }}" required>
            @error('brazo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="peso" class="form-label">Peso <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="peso" name="peso" class="form-control" value="{{ old('peso', $medida->peso ?? '') }}" required>
            @error('peso')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="altura" class="form-label">Altura <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="altura" name="altura" class="form-control" value="{{ old('altura', $medida->altura ?? '') }}" required>
            <div class="form-text">Captura la altura en cm.</div>
            @error('altura')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="imc" class="form-label">IMC <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" id="imc" name="imc" class="form-control" value="{{ old('imc', $medida->imc ?? '') }}" required>
            <div class="form-text">Se calcula automáticamente con peso y altura, pero puedes ajustarlo manualmente.</div>
            @error('imc')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
            <label for="porcentaje_grasa" class="form-label">Porcentaje de grasa <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" max="100" id="porcentaje_grasa" name="porcentaje_grasa" class="form-control" value="{{ old('porcentaje_grasa', $medida->porcentaje_grasa ?? '') }}" required>
            @error('porcentaje_grasa')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end pt-4 border-top mt-4">
        <a href="{{ $returnTo ?? route('medidas.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>{{ $editando ? 'Actualizar Medida' : 'Guardar Medida' }}
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pesoInput = document.getElementById('peso');
        const alturaInput = document.getElementById('altura');
        const imcInput = document.getElementById('imc');

        if (!pesoInput || !alturaInput || !imcInput) {
            return;
        }

        let imcManual = false;
        let ultimoImcAutomatico = '';

        function normalizarNumero(valor) {
            return Number.parseFloat(String(valor || '').replace(',', '.'));
        }

        function calcularImc() {
            const peso = normalizarNumero(pesoInput.value);
            const altura = normalizarNumero(alturaInput.value);

            if (!Number.isFinite(peso) || !Number.isFinite(altura) || peso <= 0 || altura <= 0) {
                return null;
            }

            const alturaMetros = altura > 3 ? altura / 100 : altura;

            if (alturaMetros <= 0) {
                return null;
            }

            return (peso / (alturaMetros * alturaMetros)).toFixed(2);
        }

        function actualizarImcAutomatico() {
            const imcCalculado = calcularImc();

            if (imcCalculado === null) {
                if (!imcManual) {
                    imcInput.value = '';
                    ultimoImcAutomatico = '';
                }

                return;
            }

            if (!imcManual || imcInput.value === '' || imcInput.value === ultimoImcAutomatico) {
                imcInput.value = imcCalculado;
                ultimoImcAutomatico = imcCalculado;
                imcManual = false;
            }
        }

        imcInput.addEventListener('input', function () {
            const valorActual = imcInput.value.trim();

            if (valorActual === '') {
                imcManual = false;
                actualizarImcAutomatico();
                return;
            }

            imcManual = valorActual !== ultimoImcAutomatico;
        });

        pesoInput.addEventListener('input', actualizarImcAutomatico);
        alturaInput.addEventListener('input', actualizarImcAutomatico);

        actualizarImcAutomatico();
    });
</script>
