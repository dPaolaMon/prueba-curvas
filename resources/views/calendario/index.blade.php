<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Calendario de Rutinas</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('calendario.index') }}" class="link-underline-opacity-0 link-body-emphasis">Rutinas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('calendario.index') }}" class="link-underline-opacity-0 link-body-emphasis">Calendario</a></li>
        </ol>
    </x-slot>

    
    <div class="container-fluid py-4">

        {{-- Controles de navegación --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 p-3 bg-light rounded-2 border">
            <div class="d-flex gap-2">
                <h5 class="mb-0">{{ $meses[$mes] }} {{ $anio }}</h5>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('calendario.index', ['mes' => $mes == 1 ? 12 : $mes - 1, 'anio' => $mes == 1 ? $anio - 1 : $anio]) }}"
                   class="btn btn-outline-secondary" title="Mes anterior">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <select id="mesSelect" class="form-select" style="max-width: 150px;" onchange="cambiarMes()">
                    @foreach($meses as $num => $nombre)
                        <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
                <a href="{{ route('calendario.index') }}"
                   class="btn btn-primary">Hoy</a>
                <select id="anioSelect" class="form-select" style="max-width: 100px;" onchange="cambiarAnio()">
                    @foreach($intervalo as $a)
                        <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
                <a href="{{ route('calendario.index', ['mes' => $mes == 12 ? 1 : $mes + 1, 'anio' => $mes == 12 ? $anio + 1 : $anio]) }}"
                   class="btn btn-outline-secondary" title="Mes siguiente">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        {{-- Toast de éxito --}}
        @if(session('success'))
            <script>
                if (window.Swal) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2200,
                    });
                }
            </script>
        @endif

        {{-- Tabla del calendario --}}
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="btn-primary">
                    <tr>
                        <th class="text-center" style="width: 50px;">Semana</th>
                        <th class="text-center" style="width: 120px;">Máquinas</th>
                        <th class="text-center">Lunes</th>
                        <th class="text-center">Martes</th>
                        <th class="text-center">Miércoles</th>
                        <th class="text-center">Jueves</th>
                        <th class="text-center">Viernes</th>
                        <th class="text-center">Sábado</th>
                        <th class="text-center">Domingo</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $semanaActual = null;
                        $row = null;
                        $rowAbierta = false;
                        $ultimoDiaSemanaIndice = null;
                    @endphp

                    @foreach($diasDelMes as $fecha => $dia)
                        @php
                            $carbonda = \Carbon\Carbon::parse($fecha);
                            $diaSemana = $carbonda->dayOfWeek; // 0=domingo, 1=lunes...
                            $diaSemanaIndice = $diaSemana === 0 ? 7 : $diaSemana; // 1=lunes ... 7=domingo

                            // Si cambia la semana o es el primer día, crear nueva fila
                            if ($semanaActual !== $dia['numSemana']) {
                                if ($rowAbierta) {
                                    echo '</tr>';
                                }
                                $semanaActual = $dia['numSemana'];
                                $rowAbierta = true;
                                echo '<tr>';
                                // Celda de número de semana
                                echo '<td class="text-center fw-semibold bg-light" style="vertical-align: top;">' . $dia['numSemana'] . '</td>';
                                // Celda de máquinas de la semana
                                echo '<td class="bg-light" style="vertical-align: top;">';
                                echo generarCeldaMaquinasSemana($semanas[$dia['numSemana']], $dia['numSemana'], $mes, $anio);
                                echo '</td>';

                                // Rellenar celdas vacías antes del primer día de la semana
                                if ($diaSemanaIndice > 1) {
                                    for ($i = 1; $i < $diaSemanaIndice; $i++) {
                                        echo '<td class="bg-light"></td>';
                                    }
                                }
                            }
                        @endphp

                        {{-- Celda del día --}}
                        <td class="{{ $dia['esHoy'] ? 'table-warning' : '' }}" style="height: 150px; vertical-align: top;">
                            <div class="fw-semibold mb-2 {{ $dia['esHoy'] ? 'text-danger' : '' }}" style="font-size: 0.9rem;">
                                {{ $dia['dia'] }}
                            </div>

                            {{-- Eventos del día --}}
                            <div class="d-flex flex-column gap-1" style="font-size: 0.8rem;">
                                @foreach($dia['eventos'] as $evento)
                                    @php
                                        $textoEvento = '#666';
                                        $colorCerrar = '#dc2626';
                                        if (!$evento['es_nota']) {
                                            $textoEvento = pickTextColorBasedOnBgColorSimple($evento['color'], '#ffffff', '#111827');
                                            $colorCerrar = $textoEvento;
                                        }
                                    @endphp
                                    <div class="px-2 py-1 rounded d-flex justify-content-between align-items-center evento-item" 
                                         style="background-color: {{ !$evento['es_nota'] ? $evento['color'] : 'transparent' }}; color: {{ $textoEvento }}; cursor: pointer;"
                                         onmouseover="this.querySelector('.evento-close')?.classList.remove('d-none')"
                                         onmouseout="this.querySelector('.evento-close')?.classList.add('d-none')">
                                        <span class="flex-grow-1 {{ $evento['es_nota'] ? 'fst-italic' : 'fw-medium' }}">
                                            {{ $evento['ejercicio'] }}
                                        </span>

                                        <button type="button" class="btn-close btn-sm evento-close d-none ms-1" 
                                                onclick="eliminarEvento({{ $evento['id'] }})" title="Eliminar"></button>
                                    </div>
                                @endforeach
                                
                                <!-- Botón para agregar evento -->
                                <button type="button" class="btn btn-sm btn-link text-primary p-0 mt-1" onclick="abrirMenuDia(event, '{{ $fecha }}')" style="font-size: 0.8rem;">+ Agregar</button>
                            </div>
                        </td>

                        @php
                            $ultimoDiaSemanaIndice = $diaSemanaIndice;
                            if ($diaSemanaIndice === 7) {
                                echo '</tr>';
                                $rowAbierta = false;
                            }
                        @endphp
                    @endforeach

                    @if($rowAbierta)
                        @php
                            for ($i = ($ultimoDiaSemanaIndice ?? 0) + 1; $i <= 7; $i++) {
                                echo '<td class="bg-light"></td>';
                            }
                        @endphp
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

@php
    function generarCeldaMaquinasSemana($maquinas, $numSemana, $mes, $anio) {
        $html = '<div class="d-flex flex-column gap-1">';

        if (empty($maquinas)) {
            $html .= '<button type="button" class="btn btn-sm btn-link text-success p-0" style="font-size: 0.8rem; text-decoration: none;" onclick="abrirModalAsignarMaquina(' . $numSemana . ')">+ Asignar</button>';
        } else {
            foreach ($maquinas as $ms) {
                $html .= '<div class="bg-info bg-opacity-10 text-info px-2 py-1 rounded d-flex justify-content-between align-items-center evento-item" style="font-size: 0.8rem;" onmouseover="this.querySelector(\'.maquina-close\')?.classList.remove(\'d-none\')" onmouseout="this.querySelector(\'.maquina-close\')?.classList.add(\'d-none\')">';
                $html .= '<span class="flex-grow-1">' . $ms['maquina']['nombre'] . '</span>';
                $html .= '<button type="button" class="btn-close btn-sm ms-1 d-none maquina-close" ';
                $html .= 'onclick="eliminarMaquinaSemana(' . $ms['id'] . ')" title="Eliminar"></button>';
                $html .= '</div>';
            }
            $html .= '<button type="button" class="btn btn-sm btn-link text-success p-0 mt-1" style="font-size: 0.8rem; text-decoration: none;" onclick="abrirModalAsignarMaquina(' . $numSemana . ')">+ Otra</button>';
        }

        $html .= '</div>';
        return $html;
    }

    function pickTextColorBasedOnBgColorSimple($bgColor, $lightColor = '#ffffff', $darkColor = '#111827') {
        $color = str_replace("#", "", $bgColor);
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        return (($r * 299 + $g * 587 + $b * 114) / 1000) > 128 ? $darkColor : $lightColor;
    }
@endphp

<script>
    function cambiarMes() {
        const mes = document.getElementById('mesSelect').value;
        const anio = document.getElementById('anioSelect').value;
        window.location.href = `{{ route('calendario.index') }}?mes=${mes}&anio=${anio}`;
    }

    function cambiarAnio() {
        const mes = document.getElementById('mesSelect').value;
        const anio = document.getElementById('anioSelect').value;
        window.location.href = `{{ route('calendario.index') }}?mes=${mes}&anio=${anio}`;
    }

    function abrirModalAsignarMaquina(numSemana) {
        window.Swal.fire({
            title: 'Asignar máquina a la semana',
            html: `
                <div class="mb-3">
                    <label class="form-label">Máquina <span class="text-danger">*</span></label>
                    <select id="maquinaSelect" class="form-select" required>
                        <option value="">-- Seleccione una máquina --</option>
                        @foreach(\App\Models\Maquina::orderBy('nombre')->get() as $maquina)
                            <option value="{{ $maquina->id }}">{{ $maquina->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            `,
            didOpen: (modal) => {
                modal.querySelector('#maquinaSelect').focus();
            },
            showCancelButton: true,
            confirmButtonText: 'Asignar',
            confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            preConfirm: () => {
                const select = document.getElementById('maquinaSelect');
                if (!select.value) {
                    Swal.showValidationMessage('Debe seleccionar una máquina');
                    return false;
                }
                return guardarMaquinaSemanaAjax(select.value, numSemana);
            }
        });
    }

    function guardarMaquinaSemanaAjax(maquinaId, numSemana) {
        const formData = {
            maquina_id: maquinaId,
            num_semana: numSemana,
            mes: {{ $mes }},
            anio: {{ $anio }},
            _token: '{{ csrf_token() }}'
        };

        return fetch('{{ route("calendario.asignar-maquina") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Máquina asignada correctamente',
                    showConfirmButton: false,
                    timer: 2000,
                });
                setTimeout(() => location.reload(), 500);
            } else {
                throw new Error(data.error || 'Error al asignar máquina');
            }
        })
        .catch(error => {
            window.Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al asignar máquina'
            });
        });
    }

    function abrirMenuDia(event, fecha) {
        abrirModalCrearEvento(fecha);
    }

    function abrirModalCrearEvento(fecha) {
        window.Swal.fire({
            title: 'Agregar evento',
            html: `
                <div class="mb-3">
                    <label class="form-label">Tipo de entrada</label>
                    <div class="d-flex gap-3 justify-content-center">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipoEntrada" id="tipoEjercicio" value="ejercicio" checked onchange="cambiarTipoEntrada()">
                            <label class="form-check-label" for="tipoEjercicio">Ejercicio</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipoEntrada" id="tipoNota" value="nota" onchange="cambiarTipoEntrada()">
                            <label class="form-check-label" for="tipoNota">Nota</label>
                        </div>
                    </div>
                </div>

                <div id="seccionEjercicio">
                    <div class="mb-3">
                        <label class="form-label">Ejercicio <span class="text-danger">*</span></label>
                        <select id="ejercicioInput" class="form-select" required>
                            <option value="">-- Seleccione un ejercicio --</option>
                            @foreach($todosEjercicos as $ej)
                                <option value="{{ $ej->nombre }}" data-color="{{ $ej->color }}">{{ $ej->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Color <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <input type="color" id="colorEvento" class="form-control form-control-color" value="#E91E63" required>
                            <input type="text" id="colorTexto" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div id="seccionNota" class="d-none">
                    <div class="mb-3">
                        <label class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea id="notaDescripcion" rows="4" class="form-control" placeholder="Describa la nota"></textarea>
                    </div>
                </div>
            `,
            didOpen: (modal) => {
                document.getElementById('diaEvento').value = fecha;
                document.getElementById('ejercicioInput')?.focus();
                const ejercicioSelect = document.getElementById('ejercicioInput');
                const colorInput = document.getElementById('colorEvento');
                if (colorInput) {
                    colorInput.addEventListener('input', function() {
                        document.getElementById('colorTexto').value = this.value;
                    });
                    document.getElementById('colorTexto').value = colorInput.value;
                }

                if (ejercicioSelect && colorInput) {
                    ejercicioSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const colorEjercicio = selectedOption?.dataset?.color;
                        if (colorEjercicio) {
                            colorInput.value = colorEjercicio;
                            document.getElementById('colorTexto').value = colorEjercicio;
                        }
                    });
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            preConfirm: () => {
                const tipo = document.querySelector('input[name="tipoEntrada"]:checked').value;
                let ejercicio, color, esNota;
                
                if (tipo === 'ejercicio') {
                    ejercicio = document.getElementById('ejercicioInput').value;
                    color = document.getElementById('colorEvento').value;
                    esNota = false;
                    if (!ejercicio) {
                        Swal.showValidationMessage('Debe seleccionar un ejercicio');
                        return false;
                    }
                } else {
                    ejercicio = document.getElementById('notaDescripcion').value;
                    color = '#999999';
                    esNota = true;
                    if (!ejercicio) {
                        Swal.showValidationMessage('Debe ingresar la descripción de la nota');
                        return false;
                    }
                }

                return guardarEventoAjax(fecha, ejercicio, color, esNota);
            }
        });
    }

    function cambiarTipoEntrada() {
        const tipo = document.querySelector('input[name="tipoEntrada"]:checked').value;
        if (tipo === 'ejercicio') {
            document.getElementById('seccionEjercicio')?.classList.remove('d-none');
            document.getElementById('seccionNota')?.classList.add('d-none');
        } else {
            document.getElementById('seccionEjercicio')?.classList.add('d-none');
            document.getElementById('seccionNota')?.classList.remove('d-none');
        }
    }

    function guardarEventoAjax(dia, ejercicio, color, esNota) {
        const formData = {
            dia: dia,
            ejercicio: ejercicio,
            color: color,
            es_nota: esNota,
            _token: '{{ csrf_token() }}'
        };

        return fetch('{{ route("calendario.crear-evento") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Evento creado correctamente',
                    showConfirmButton: false,
                    timer: 2000,
                });
                setTimeout(() => location.reload(), 500);
            } else {
                throw new Error(data.error || 'Error al crear evento');
            }
        })
        .catch(error => {
            window.Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al crear evento'
            });
        });
    }

    function eliminarEvento(eventoId) {
        window.Swal.fire({
            title: 'Eliminar evento',
            text: '¿Está seguro de que desea eliminar este evento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            fetch(`{{ route('calendario.eliminar-evento', ':id') }}`.replace(':id', eventoId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.success,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(() => location.reload(), 500);
                }
            });
        });
    }

    function eliminarMaquinaSemana(id) {
        window.Swal.fire({
            title: 'Eliminar máquina',
            text: '¿Está seguro de que desea eliminar esta máquina de la semana?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            fetch(`{{ route('calendario.eliminar-maquina-semana', ':id') }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.success,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(() => location.reload(), 500);
                }
            });
        });
    }

    // Para mantener compatibilidad con referencias globales
    let diaEventoInput = document.createElement('input');
    diaEventoInput.type = 'hidden';
    diaEventoInput.id = 'diaEvento';
    document.body.appendChild(diaEventoInput);
</script>
