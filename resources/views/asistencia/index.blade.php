<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Registro Manual de Asistencia</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Socias') }}</a></li>
            <li class="breadcrumb-item">{{ __('Asistencia Manual') }}</li>
        </ol>
    </x-slot>

    @php
        $sociaSeleccionTexto = '';
        $sociasCatalogo = $socias
            ->map(function ($socia) {
                return [
                    'id' => $socia->id,
                    'num_socia' => (string) $socia->num_socia,
                    'nombre_completo' => trim($socia->nombre . ' ' . $socia->apellidos),
                    'label' => '#' . $socia->num_socia . ' - ' . trim($socia->nombre . ' ' . $socia->apellidos),
                ];
            })
            ->values();

        if (!empty($sociaId)) {
            $sociaActual = $socias->firstWhere('id', (int) $sociaId);
            if ($sociaActual) {
                $sociaSeleccionTexto = "#{$sociaActual->num_socia} - {$sociaActual->nombre} {$sociaActual->apellidos}";
            }
        }
    @endphp

    <div class="container py-4">
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-4">Registrar Asistencia</h3>

                        <form id="registroForm">
                            @csrf

                            <div class="mb-3">
                                <label for="socia_search" class="form-label">Buscar socia</label>
                                <input
                                    type="text"
                                    id="socia_search"
                                    list="socias_autocomplete"
                                    value="{{ $sociaSeleccionTexto }}"
                                    placeholder="Escribe número o nombre de socia"
                                    class="form-control"
                                    autocomplete="off"
                                >
                                <datalist id="socias_autocomplete">
                                    @foreach($socias as $socia)
                                        <option value="#{{ $socia->num_socia }} - {{ $socia->nombre }} {{ $socia->apellidos }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="mb-3">
                                <label for="socia_id" class="form-label">Socia</label>
                                <select name="socia_id" id="socia_id" class="form-select">
                                    <option value="">Selecciona una socia...</option>
                                    @foreach($socias as $socia)
                                        <option value="{{ $socia->id }}" {{ (string) $sociaId === (string) $socia->id ? 'selected' : '' }}>
                                            #{{ $socia->num_socia }} - {{ $socia->nombre }} {{ $socia->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input
                                    type="date"
                                    name="fecha"
                                    id="fecha"
                                    value="{{ $fecha }}"
                                    class="form-control"
                                >
                            </div>

                            <div class="mb-3">
                                <label for="hora" class="form-label">Hora</label>
                                <input
                                    type="time"
                                    name="hora"
                                    id="hora"
                                    value="{{ $hora }}"
                                    class="form-control"
                                >
                            </div>

                            <div id="statusArea" class="alert alert-secondary mb-4" role="status">
                                Selecciona socia, fecha y hora para continuar.
                            </div>

                            <div class="d-grid">
                                <button type="button" id="toggleBtn" class="btn btn-secondary" disabled>
                                    <i class="bi bi-info-circle me-2"></i>Selecciona socia, fecha y hora
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        @if($sociaSeleccionada)
                            <div class="border-bottom pb-3 mb-4">
                                <h3 class="h5 mb-1">{{ $sociaSeleccionada->nombre }} {{ $sociaSeleccionada->apellidos }}</h3>
                                <p class="text-body-secondary mb-0">Número de Socia: #{{ $sociaSeleccionada->num_socia }}</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
                                <h4 class="h6 mb-0">Últimas 10 asistencias</h4>
                                @if($asistenciaEnFecha)
                                    <span class="badge text-bg-success">Con asistencia en la fecha seleccionada</span>
                                @elseif(!empty($fecha))
                                    <span class="badge text-bg-secondary">Sin asistencia en la fecha seleccionada</span>
                                @endif
                            </div>

                            @if($asistenciasRecientes->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($asistenciasRecientes as $asistencia)
                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                            <div>
                                                <div>{{ \Carbon\Carbon::parse($asistencia->fecha)->locale('es')->isoFormat('DD/MM/YYYY (dddd)') }}</div>
                                                <div class="small text-body-secondary">{{ \Carbon\Carbon::parse($asistencia->hora)->format('H:i') }} hrs</div>
                                            </div>
                                            <span class="badge text-bg-success">Registrada</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-light border text-center mb-0" role="alert">
                                    Sin asistencias registradas.
                                </div>
                            @endif
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-center py-5 h-100 text-body-secondary">
                                <i class="bi bi-person-lines-fill fs-1 mb-3"></i>
                                <h3 class="h5 text-body mb-2">Selecciona una socia</h3>
                                <p class="mb-0">Para ver su información y últimas asistencias.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sociaInput = document.getElementById('socia_id');
            const sociaSearchInput = document.getElementById('socia_search');
            const fechaInput = document.getElementById('fecha');
            const horaInput = document.getElementById('hora');
            const toggleBtn = document.getElementById('toggleBtn');
            const statusArea = document.getElementById('statusArea');
            const sociasCatalogo = @json($sociasCatalogo);
            const themeColor = getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd';

            function mostrarToast(icon, title) {
                if (!window.Swal) {
                    return;
                }

                window.Swal.fire({
                    toast: true,
                    theme: 'auto',
                    position: 'top-end',
                    icon,
                    title,
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true,
                });
            }

            function obtenerJsonSeguro(response) {
                return response.json().catch(function () {
                    return {};
                });
            }

            function sincronizarSelectDesdeBusqueda() {
                const termino = (sociaSearchInput.value || '').trim().toLowerCase();

                if (!termino) {
                    sociaInput.value = '';
                    verificarAsistencia();
                    return;
                }

                let socia = sociasCatalogo.find(item => item.label.toLowerCase() === termino);

                if (!socia) {
                    socia = sociasCatalogo.find(item => item.num_socia === termino.replace('#', ''));
                }

                if (!socia) {
                    socia = sociasCatalogo.find(item => item.nombre_completo.toLowerCase().includes(termino));
                }

                if (!socia) {
                    mostrarToast('error', 'No se encontró una socia con ese criterio');
                    return;
                }

                sociaInput.value = socia.id;
                sociaSearchInput.value = socia.label;
                cargarAsistencias();
            }

            function sincronizarBusquedaDesdeSelect() {
                const sociaId = sociaInput.value;
                const socia = sociasCatalogo.find(item => String(item.id) === String(sociaId));
                sociaSearchInput.value = socia ? socia.label : '';
            }

            function actualizarBoton(habilitado, existe) {
                toggleBtn.disabled = !habilitado;

                if (!habilitado) {
                    toggleBtn.className = 'btn btn-secondary';
                    toggleBtn.innerHTML = '<i class="bi bi-info-circle me-2"></i>Selecciona socia, fecha y hora';
                    toggleBtn.onclick = null;
                    statusArea.className = 'alert alert-secondary mb-4';
                    statusArea.innerHTML = 'Selecciona socia, fecha y hora para continuar.';
                    return;
                }

                if (existe) {
                    toggleBtn.className = 'btn btn-danger';
                    toggleBtn.innerHTML = '<i class="bi bi-trash me-2"></i>Quitar Asistencia';
                    toggleBtn.onclick = quitarAsistencia;
                    statusArea.className = 'alert alert-success mb-4';
                    statusArea.innerHTML = '<strong>Asistencia registrada</strong><div class="small">Ya existe un registro para esta fecha.</div>';
                    return;
                }

                toggleBtn.className = 'btn btn-success';
                toggleBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Registrar Asistencia';
                toggleBtn.onclick = registrarAsistencia;
                statusArea.className = 'alert alert-light border mb-4';
                statusArea.innerHTML = '<strong>Sin registro</strong><div class="small">No existe asistencia para esta fecha.</div>';
            }

            function verificarAsistencia() {
                const sociaId = sociaInput.value;
                const fecha = fechaInput.value;
                const hora = horaInput.value;

                if (!sociaId || !fecha || !hora) {
                    actualizarBoton(false, null);
                    return;
                }

                fetch('{{ route("asistencia.verificar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        socia_id: sociaId,
                        fecha: fecha,
                    }),
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw response;
                        }

                        return response.json();
                    })
                    .then(function (data) {
                        actualizarBoton(true, data.existe);
                    })
                    .catch(function () {
                        actualizarBoton(false, null);
                        mostrarToast('error', 'No fue posible verificar la asistencia');
                    });
            }

            function registrarAsistencia(event) {
                event.preventDefault();

                const sociaId = sociaInput.value;
                const fecha = fechaInput.value;
                const hora = horaInput.value;

                toggleBtn.disabled = true;
                toggleBtn.textContent = 'Registrando...';

                fetch('{{ route("asistencia.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        socia_id: sociaId,
                        fecha: fecha,
                        hora: hora,
                    }),
                })
                    .then(function (response) {
                        return obtenerJsonSeguro(response).then(function (data) {
                            if (!response.ok) {
                                throw data;
                            }

                            return data;
                        });
                    })
                    .then(function (data) {
                        mostrarToast('success', data.message || 'Asistencia registrada correctamente');
                        setTimeout(function () {
                            window.location.href = `{{ route('asistencia.index') }}?socia_id=${sociaId}&fecha=${fecha}&hora=${hora}`;
                        }, 1200);
                    })
                    .catch(function (error) {
                        mostrarToast('error', error.message || 'Error al registrar asistencia');
                        actualizarBoton(true, false);
                    });
            }

            function quitarAsistencia(event) {
                event.preventDefault();

                window.Swal.fire({
                    title: 'Confirmar eliminación',
                    text: '¿Está seguro de que desea eliminar esta asistencia?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    confirmButtonColor: themeColor,
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    const sociaId = sociaInput.value;
                    const fecha = fechaInput.value;
                    const hora = horaInput.value;

                    toggleBtn.disabled = true;
                    toggleBtn.textContent = 'Eliminando...';

                    fetch('{{ route("asistencia.destroy") }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            socia_id: sociaId,
                            fecha: fecha,
                        }),
                    })
                        .then(function (response) {
                            return obtenerJsonSeguro(response).then(function (data) {
                                if (!response.ok) {
                                    throw data;
                                }

                                return data;
                            });
                        })
                        .then(function (data) {
                            mostrarToast('success', data.message || 'Asistencia eliminada correctamente');
                            setTimeout(function () {
                                window.location.href = `{{ route('asistencia.index') }}?socia_id=${sociaId}&fecha=${fecha}&hora=${hora}`;
                            }, 1200);
                        })
                        .catch(function (error) {
                            mostrarToast('error', error.message || 'Error al eliminar asistencia');
                            actualizarBoton(true, true);
                        });
                });
            }

            function cargarAsistencias() {
                const sociaId = sociaInput.value;
                const fecha = fechaInput.value;
                const hora = horaInput.value;

                sincronizarBusquedaDesdeSelect();

                if (sociaId && fecha) {
                    window.location.href = `{{ route('asistencia.index') }}?socia_id=${sociaId}&fecha=${fecha}&hora=${hora}`;
                    return;
                }

                verificarAsistencia();
            }

            sociaInput.addEventListener('change', cargarAsistencias);
            fechaInput.addEventListener('change', verificarAsistencia);
            horaInput.addEventListener('change', verificarAsistencia);
            sociaSearchInput.addEventListener('change', sincronizarSelectDesdeBusqueda);
            sociaSearchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    sincronizarSelectDesdeBusqueda();
                }
            });

            sincronizarBusquedaDesdeSelect();

            if (sociaInput.value && fechaInput.value && horaInput.value) {
                verificarAsistencia();
            }
        });
    </script>
</x-app-layout>
