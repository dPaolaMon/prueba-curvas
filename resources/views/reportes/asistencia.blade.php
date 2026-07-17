<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Reporte de Asistencia</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Socias') }}</a></li>            
            <li class="breadcrumb-item"><a href="#" class="link-underline-opacity-0 link-body-emphasis">{{ __('Reporte de asistencia') }}</a></li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('reportes.asistencia') }}" id="filtroForm" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-5">
                            <label for="search" class="form-label">Buscar socia</label>
                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ $search ?? '' }}"
                                class="form-control"
                                placeholder="Número de socia o nombre"
                            >
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="filtro" class="form-label">Mostrar registros de</label>
                            <select name="filtro" id="filtro" class="form-select">
                                <option value="1" {{ $filtro == '1' ? 'selected' : '' }}>La presente semana</option>
                                <option value="2" {{ $filtro == '2' ? 'selected' : '' }}>Lo que va del mes</option>
                                <option value="3" {{ $filtro == '3' ? 'selected' : '' }}>Mes anterior</option>
                                <option value="4" {{ $filtro == '4' ? 'selected' : '' }}>Últimos dos meses</option>
                                <option value="5" {{ $filtro == '5' ? 'selected' : '' }}>Último semestre</option>
                                <option value="6" {{ $filtro == '6' ? 'selected' : '' }}>Lo que va del año</option>
                                <option value="7" {{ $filtro == '7' ? 'selected' : '' }}>Personalizado</option>
                            </select>
                        </div>

                        <div id="fechaInicioDiv" class="col-12 col-md-3 d-none">
                            <label for="fecha_inicio" class="form-label">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio }}" class="form-control">
                        </div>

                        <div id="fechaFinDiv" class="col-12 col-md-3 d-none">
                            <label for="fecha_fin" class="form-label">Fecha fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $fechaFin }}" class="form-control">
                        </div>

                        <div class="col-12 col-md-auto d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-clipboard-data me-2"></i>Generar Reporte
                            </button>

                            @if(!empty($search) || request()->has('filtro'))
                                <a href="{{ route('reportes.asistencia') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                                </a>
                            @endif

                            @if(count($datosReporte) > 0)
                                <button type="button" id="btnExportarCsv" class="btn btn-outline-secondary">
                                    <i class="bi bi-download me-2"></i>Exportar CSV
                                </button>
                            @endif

                            <button type="button" id="btnAsistenciaHoy" class="btn btn-outline-secondary">
                                <i class="bi bi-calendar-check me-2"></i>Asistencia de hoy
                            </button>
                            <button type="button" id="btnAusenciasConsecutivas" class="btn btn-outline-secondary">
                                <i class="bi bi-calendar-x me-2"></i>Ausencias consecutivas
                            </button>
                        </div>
                    </div>
                </form>

                @if(count($datosReporte) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="bg-body-secondary">Num Socia</th>
                                    <th scope="col">Nombre</th>
                                    @if($mostrarDetalle)
                                        @foreach($fechas as $fecha)
                                            <th scope="col" class="text-center text-nowrap">
                                                {{ $fecha->locale('es')->isoFormat('DD/MM') }}<br>
                                                <span class="fw-normal text-body-secondary">{{ $fecha->locale('es')->isoFormat('ddd') }}</span>
                                            </th>
                                        @endforeach
                                    @endif
                                    <th scope="col" class="text-center bg-body-secondary">
                                        @php
                                            $totalDirection = (($sort ?? 'total') === 'total' && ($direction ?? 'desc') === 'asc') ? 'desc' : 'asc';
                                        @endphp
                                        <a href="{{ route('reportes.asistencia', array_merge(request()->query(), ['sort' => 'total', 'direction' => $totalDirection])) }}" class="link-body-emphasis text-decoration-none">
                                            <span>Total</span>
                                            @if(($sort ?? 'total') === 'total')
                                                <span class="ms-1">{{ ($direction ?? 'desc') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datosReporte as $fila)
                                    <tr>
                                        <td class="fw-semibold">{{ $fila['num_socia'] }}</td>
                                        <td>{{ $fila['nombre'] }}</td>
                                        @if($mostrarDetalle)
                                            @foreach($fechas as $fecha)
                                                @php
                                                    $fechaStr = $fecha->format('Y-m-d');
                                                    $asistio = $fila['asistencias_detalle'][$fechaStr] ?? false;
                                                @endphp
                                                <td class="text-center">
                                                    @if($asistio)
                                                        <span class="text-success fs-6">✓</span>
                                                    @else
                                                        <span class="text-body-secondary">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endif
                                        <td class="text-center fw-semibold bg-body-secondary">{{ $fila['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 small text-body-secondary">
                        <p class="mb-1"><strong>Total de socias:</strong> {{ count($datosReporte) }}</p>
                        <p class="mb-0"><strong>Período:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->locale('es')->isoFormat('DD/MM/YYYY') }} al {{ \Carbon\Carbon::parse($fechaFin)->locale('es')->isoFormat('DD/MM/YYYY') }}</p>
                    </div>
                @else
                    <div class="text-center py-5 text-body-secondary">
                        <i class="bi bi-clipboard-data fs-1 d-block mb-3"></i>
                        <h3 class="h6 text-body mb-2">No hay datos para mostrar</h3>
                        <p class="mb-0">Selecciona un período para generar el reporte.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filtroForm = document.getElementById('filtroForm');
            const filtroSelect = document.getElementById('filtro');
            const fechaInicioDiv = document.getElementById('fechaInicioDiv');
            const fechaFinDiv = document.getElementById('fechaFinDiv');
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaFinInput = document.getElementById('fecha_fin');
            const btnExportarCsv = document.getElementById('btnExportarCsv');
            const btnAsistenciaHoy = document.getElementById('btnAsistenciaHoy');
            const btnAusenciasConsecutivas = document.getElementById('btnAusenciasConsecutivas');
            const asistenciasHoy = @json($asistenciasHoy);
            const ausenciasConsecutivas = @json($ausenciasConsecutivas);
            const themeColor = getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd';

            function mostrarToast(icon, title) {
                if (!window.Swal) return;

                window.Swal.fire({
                    toast: true,
                    theme: 'auto',
                    position: 'top-end',
                    icon: icon,
                    title: title,
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true,
                });
            }

            function toggleFechasPersonalizadas() {
                const personalizado = filtroSelect.value === '7';
                fechaInicioDiv.classList.toggle('d-none', !personalizado);
                fechaFinDiv.classList.toggle('d-none', !personalizado);
            }

            function validarRangoPersonalizado() {
                if (filtroSelect.value !== '7') {
                    return true;
                }

                const inicio = fechaInicioInput.value;
                const fin = fechaFinInput.value;

                if (!inicio || !fin) {
                    window.Swal.fire({
                        icon: 'warning',
                        title: 'Fechas requeridas',
                        text: 'Para filtro personalizado debes indicar fecha inicio y fecha fin.',
                        confirmButtonColor: themeColor,
                    });
                    return false;
                }

                if (inicio > fin) {
                    window.Swal.fire({
                        icon: 'error',
                        title: 'Rango inválido',
                        text: 'La fecha inicio no puede ser mayor a la fecha fin.',
                        confirmButtonColor: themeColor,
                    });
                    return false;
                }

                return true;
            }

            function exportarCSV() {
                if (!validarRangoPersonalizado()) {
                    return;
                }

                window.Swal.fire({
                    title: 'Exportar reporte',
                    text: '¿Deseas exportar el reporte actual en formato CSV?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, exportar',
                    confirmButtonColor: themeColor,
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    const params = new URLSearchParams(new FormData(filtroForm));
                    window.location.href = '{{ route("reportes.asistencia.export") }}?' + params.toString();
                });
            }

            function mostrarAsistenciaHoy() {
                if (!Array.isArray(asistenciasHoy) || asistenciasHoy.length === 0) {
                    window.Swal.fire({
                        icon: 'info',
                        title: 'Sin asistencias hoy',
                        text: 'No hay socias activas con asistencia registrada en el día de hoy.',
                        confirmButtonColor: themeColor,
                    });
                    return;
                }

                const filas = asistenciasHoy.map(function (item) {
                    const numero = item.num_socia ? '#' + item.num_socia : '#—';
                    const nombre = item.nombre || 'Socia sin nombre';
                    const hora = item.hora ? item.hora + ' h' : 'sin hora';
                    return `<tr><td class="text-nowrap">${numero}</td><td>${nombre}</td><td class="text-nowrap text-end">${hora}</td></tr>`;
                }).join('');

                const html = `
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Num Socia</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col" class="text-end">Hora</th>
                                </tr>
                            </thead>
                            <tbody>${filas}</tbody>
                        </table>
                    </div>
                `;

                window.Swal.fire({
                    title: `Asistencia de hoy (${asistenciasHoy.length})`,
                    html: html,
                    width: 760,
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: themeColor,
                });
            }

            function mostrarAusenciasConsecutivas() {
                if (!Array.isArray(ausenciasConsecutivas) || ausenciasConsecutivas.length === 0) {
                    window.Swal.fire({
                        icon: 'info',
                        title: 'Sin ausencias consecutivas',
                        text: 'No hay socias activas con más de 2 ausencias seguidas.',
                        confirmButtonColor: themeColor,
                    });
                    return;
                }

                const filas = ausenciasConsecutivas.map(function (item) {
                    const numero = item.num_socia ? '#' + item.num_socia : '#—';
                    const nombre = item.nombre || 'Socia sin nombre';
                    const dias = item.dias_ausencia + ' días';
                    const ultima = item.ultima_asistencia || 'Sin asistencias registradas';

                    return `<tr><td class="text-nowrap">${numero}</td><td>${nombre}</td><td class="text-end text-nowrap">${dias}</td><td class="text-nowrap">${ultima}</td></tr>`;
                }).join('');

                const html = `
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Num Socia</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col" class="text-end">Días sin asistir</th>
                                    <th scope="col">Última asistencia</th>
                                </tr>
                            </thead>
                            <tbody>${filas}</tbody>
                        </table>
                    </div>
                `;

                window.Swal.fire({
                    title: `Ausencias consecutivas (${ausenciasConsecutivas.length})`,
                    html: html,
                    width: 820,
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: themeColor,
                });
            }

            filtroSelect.addEventListener('change', toggleFechasPersonalizadas);

            filtroForm.addEventListener('submit', function (event) {
                if (!validarRangoPersonalizado()) {
                    event.preventDefault();
                }
            });

            if (btnExportarCsv) {
                btnExportarCsv.addEventListener('click', exportarCSV);
            }

            if (btnAsistenciaHoy) {
                btnAsistenciaHoy.addEventListener('click', mostrarAsistenciaHoy);
            }

            if (btnAusenciasConsecutivas) {
                btnAusenciasConsecutivas.addEventListener('click', mostrarAusenciasConsecutivas);
            }

            toggleFechasPersonalizadas();

            @if(request()->has('filtro') && count($datosReporte) === 0)
                mostrarToast('info', 'No se encontraron datos para el período seleccionado');
            @endif
        });
    </script>
</x-app-layout>
