<x-app-layout>
    @php
        $destinatariosUi = $destinatariosDisponibles->map(function ($usuario) {
            return [
                'id' => (int) $usuario->id,
                'name' => $usuario->name,
                'role' => ucfirst(strtolower((string) $usuario->role)),
            ];
        })->values();

        $destinatariosInicialesUi = collect(old('destinatarios', []))
            ->map(function ($id) {
                return (int) $id;
            })
            ->values();
    @endphp

    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Redactar Mensaje') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('mensajes.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Mensajería') }}</a></li>
            <li class="breadcrumb-item">{{ __('Redactar') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">

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

                        <form method="POST" action="{{ route('mensajes.store') }}">
                            @csrf

                            {{-- Destinatarios --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                    <label for="destinatarios" class="form-label fw-semibold mb-0">
                                        Para <span class="text-danger">*</span>
                                    </label>
                                    <div class="d-flex gap-1">
                                        {{-- Botón Limpiar (visible para todos) --}}
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnLimpiar" title="Limpiar selección">
                                            ✕
                                        </button>
                                        
                                        {{-- Botón Todas las Socias (visible para ENTRENADORA, GERENTE, ADMINISTRADOR) --}}
                                        @if (in_array(strtoupper($usuario->role), ['ENTRENADORA', 'GERENTE', 'ADMINISTRADOR']))
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnSocias">
                                                Todas las Socias
                                            </button>
                                        @endif
                                        
                                        {{-- Botón Todos los Entrenadores (visible para GERENTE, ADMINISTRADOR) --}}
                                        @if (in_array(strtoupper($usuario->role), ['GERENTE', 'ADMINISTRADOR']))
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnEntrenadores">
                                                Todos los Entrenadores
                                            </button>
                                        @endif
                                        
                                        {{-- Botón Todos los Gerentes (visible para ADMINISTRADOR) --}}
                                        @if (strtoupper($usuario->role) === 'ADMINISTRADOR')
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnGerentes">
                                                Todos los Gerentes
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div id="destinatariosComposer" class="position-relative">
                                    <div id="destinatariosSelected" class="form-control d-flex flex-wrap gap-2 align-items-center py-2" style="min-height: 46px;">
                                        <input
                                            type="text"
                                            id="destinatariosInput"
                                            class="border-0 flex-grow-1"
                                            style="min-width: 180px; outline: none;"
                                            placeholder="Escribe nombre o rol para agregar destinatarios"
                                            autocomplete="off"
                                        >
                                    </div>
                                    <div id="destinatariosSuggestions" class="list-group position-absolute w-100 shadow-sm d-none" style="z-index: 20; max-height: 220px; overflow-y: auto;"></div>
                                    <div id="destinatariosHidden"></div>
                                </div>
                                <div class="form-text">Escribe para buscar, Enter para agregar, Backspace para quitar el último.</div>
                                @error('destinatarios')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @error('destinatarios.*')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Asunto --}}
                            <div class="mb-3">
                                <label for="asunto" class="form-label fw-semibold">Asunto</label>
                                <input
                                    type="text"
                                    id="asunto"
                                    name="asunto"
                                    class="form-control"
                                    value="{{ old('asunto') }}"
                                    maxlength="120"
                                    placeholder="(opcional)"
                                >
                                @error('asunto')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Cuerpo --}}
                            <div class="mb-3">
                                <label for="cuerpo" class="form-label fw-semibold">
                                    Mensaje <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    id="cuerpo"
                                    name="cuerpo"
                                    rows="8"
                                    class="form-control"
                                    maxlength="5000"
                                    required
                                >{{ old('cuerpo') }}</textarea>
                                @error('cuerpo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                                <a href="{{ route('mensajes.index') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const destinatariosDisponibles = @json($destinatariosUi);
            const destinatariosIniciales = @json($destinatariosInicialesUi);

            const composer = document.getElementById('destinatariosComposer');
            const selectedContainer = document.getElementById('destinatariosSelected');
            const input = document.getElementById('destinatariosInput');
            const suggestions = document.getElementById('destinatariosSuggestions');
            const hiddenContainer = document.getElementById('destinatariosHidden');

            if (!composer || !selectedContainer || !input || !suggestions || !hiddenContainer) {
                return;
            }

            const disponiblesById = new Map(destinatariosDisponibles.map(function (item) {
                return [item.id, item];
            }));
            const seleccionados = new Set();

            function crearInputHidden(id) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'destinatarios[]';
                hidden.value = String(id);
                hidden.dataset.destinatarioId = String(id);
                hiddenContainer.appendChild(hidden);
            }

            function eliminarInputHidden(id) {
                const hidden = hiddenContainer.querySelector(`input[data-destinatario-id="${id}"]`);
                if (hidden) {
                    hidden.remove();
                }
            }

            function crearChip(destinatario) {
                const chip = document.createElement('span');
                chip.className = 'badge text-bg-primary d-inline-flex align-items-center gap-2';
                chip.dataset.destinatarioId = String(destinatario.id);
                chip.textContent = `${destinatario.name} (${destinatario.role})`;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-link text-white p-0 border-0 text-decoration-none';
                removeBtn.style.lineHeight = '1';
                removeBtn.textContent = '×';
                removeBtn.setAttribute('aria-label', `Quitar a ${destinatario.name}`);
                removeBtn.addEventListener('click', function () {
                    quitarDestinatario(destinatario.id);
                });

                chip.appendChild(removeBtn);
                selectedContainer.insertBefore(chip, input);
            }

            function agregarDestinatario(id) {
                const destinatario = disponiblesById.get(Number(id));

                if (!destinatario || seleccionados.has(destinatario.id)) {
                    return;
                }

                seleccionados.add(destinatario.id);
                crearChip(destinatario);
                crearInputHidden(destinatario.id);
                input.value = '';
                renderSugerencias();
            }

            function quitarDestinatario(id) {
                const destinatarioId = Number(id);
                seleccionados.delete(destinatarioId);
                eliminarInputHidden(destinatarioId);

                const chip = selectedContainer.querySelector(`span[data-destinatario-id="${destinatarioId}"]`);
                if (chip) {
                    chip.remove();
                }

                renderSugerencias();
                input.focus();
            }

            function obtenerSugerencias() {
                const termino = input.value.trim().toLowerCase();

                return destinatariosDisponibles.filter(function (item) {
                    if (seleccionados.has(item.id)) {
                        return false;
                    }

                    if (termino === '') {
                        return true;
                    }

                    return item.name.toLowerCase().includes(termino)
                        || item.role.toLowerCase().includes(termino);
                }).slice(0, 8);
            }

            function renderSugerencias() {
                const items = obtenerSugerencias();
                suggestions.innerHTML = '';

                if (items.length === 0) {
                    suggestions.classList.add('d-none');
                    return;
                }

                items.forEach(function (item) {
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'list-group-item list-group-item-action';
                    option.textContent = `${item.name} (${item.role})`;
                    option.addEventListener('click', function () {
                        agregarDestinatario(item.id);
                    });
                    suggestions.appendChild(option);
                });

                suggestions.classList.remove('d-none');
            }

            function agregarPrimeraSugerencia() {
                const items = obtenerSugerencias();
                if (items.length === 0) {
                    return;
                }

                agregarDestinatario(items[0].id);
            }

            destinatariosIniciales.forEach(function (id) {
                if (disponiblesById.has(id)) {
                    agregarDestinatario(id);
                }
            });

            selectedContainer.addEventListener('click', function () {
                input.focus();
                renderSugerencias();
            });

            input.addEventListener('input', renderSugerencias);

            input.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === 'Tab' || event.key === ',') {
                    if (input.value.trim() !== '' || (event.key === 'Tab' && !suggestions.classList.contains('d-none'))) {
                        event.preventDefault();
                        agregarPrimeraSugerencia();
                    }
                    return;
                }

                if (event.key === 'Backspace' && input.value.trim() === '' && seleccionados.size > 0) {
                    const ultimoId = Array.from(seleccionados).pop();
                    quitarDestinatario(ultimoId);
                }
            });

            document.addEventListener('click', function (event) {
                if (!composer.contains(event.target)) {
                    suggestions.classList.add('d-none');
                }
            });

            input.addEventListener('focus', renderSugerencias);

            // Funciones para botones de filtrado por rol
            function agregarTodosPorRol(rol) {
                const usuariosPorRol = destinatariosDisponibles.filter(function (item) {
                    return item.role.toUpperCase() === rol.toUpperCase();
                });

                usuariosPorRol.forEach(function (item) {
                    if (!seleccionados.has(item.id)) {
                        agregarDestinatario(item.id);
                    }
                });
            }

            function limpiarSeleccion() {
                Array.from(seleccionados).forEach(function (id) {
                    quitarDestinatario(id);
                });
            }

            // Event listeners para botones
            const btnLimpiar = document.getElementById('btnLimpiar');
            const btnSocias = document.getElementById('btnSocias');
            const btnEntrenadores = document.getElementById('btnEntrenadores');
            const btnGerentes = document.getElementById('btnGerentes');

            if (btnLimpiar) {
                btnLimpiar.addEventListener('click', function (event) {
                    event.preventDefault();
                    limpiarSeleccion();
                });
            }

            if (btnSocias) {
                btnSocias.addEventListener('click', function (event) {
                    event.preventDefault();
                    agregarTodosPorRol('Socia');
                });
            }

            if (btnEntrenadores) {
                btnEntrenadores.addEventListener('click', function (event) {
                    event.preventDefault();
                    agregarTodosPorRol('Entrenadora');
                });
            }

            if (btnGerentes) {
                btnGerentes.addEventListener('click', function (event) {
                    event.preventDefault();
                    agregarTodosPorRol('Gerente');
                });
            }
        });
    </script>
</x-app-layout>
