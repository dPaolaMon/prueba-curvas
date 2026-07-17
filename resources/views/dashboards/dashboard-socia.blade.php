<x-app-layout>
    <style>
        .mensaje-preview-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
        }
    </style>

    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Dashboard') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Inicio') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Dashboard') }}</a></li>
        </ol>
    </x-slot>

  <div class="container">
    <!-- ÁREAS 1x2 (desktop) / vertical (mobile) -->
    <div class="row g-2">
        <!-- Área 1: Notificaciones con scroll -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Notificaciones</h5>

                    <div class="d-flex flex-column gap-3 overflow-auto pe-1" style="max-height: 500px;">
                        <div class="card card-dashboard">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-heart-pulse fs-5 text-danger me-2"></i>
                                    <strong>Hoy entrenaremos</strong>
                                </div>

                                @if($ejercicios->count() > 0)
                                    <p class="mb-1 text-muted">
                                        🏋️‍♀️
                                        @foreach($ejercicios as $index => $ejercicio)
                                            <b>{{ $ejercicio->ejercicio }}</b>@if($index < $ejercicios->count() - 1), @endif
                                        @endforeach
                                    </p>
                                @else
                                    <p class="mb-1 text-muted">Sin ejercicios asignados para hoy.</p>
                                @endif

                                @if($notas->count() > 0)
                                    @foreach($notas as $nota)
                                        <p class="mb-0">💡 {{ $nota->ejercicio }}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="card card-dashboard">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-calendar-event fs-5 text-primary me-2"></i>
                                    <strong>Próximo pago de mensualidad</strong>
                                </div>
                                <p class="mb-0 text-muted">
                                    {{ $proximoPago ? $proximoPago->format('d / m / Y') : 'Sin fecha de corte disponible' }}
                                </p>
                            </div>
                        </div>

                        @foreach($mensajesNoLeidos as $mensajeNoLeido)
                            @php
                                $mensaje = $mensajeNoLeido->mensaje;
                                if (!$mensaje) {
                                    continue;
                                }
                                $remitente = $mensaje?->remitente?->name ?? 'Remitente';
                                $asunto = trim((string) ($mensaje?->asunto ?? ''));
                                $cuerpoMensaje = trim((string) ($mensaje?->cuerpo ?? ''));
                                $textoMensaje = $asunto !== '' ? $asunto . ' - ' . $cuerpoMensaje : $cuerpoMensaje;
                            @endphp
                            <div class="card card-dashboard">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-envelope fs-5 text-primary me-2"></i>
                                        <a href="{{ route('mensajes.show', $mensaje->id) }}" class="fw-semibold link-underline-opacity-0 link-body-emphasis">
                                            <strong>Mensaje de {{ $remitente }}</strong>
                                        </a>
                                    </div>
                                    <p class="mb-1 text-muted mensaje-preview-clamp">{{ $textoMensaje !== '' ? $textoMensaje : '(sin contenido)' }}</p>
                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                        <small class="text-body-secondary">Enviado: {{ $mensaje->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</small>
                                        <a href="{{ route('mensajes.show', $mensaje->id) }}" class="small link-underline-opacity-0">Ver mensaje</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="card card-dashboard">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-clock-history fs-5 text-warning me-2"></i>
                                    <strong>Última visita</strong>
                                </div>
                                <p class="mb-0 text-muted">{{ $ultimaVisitaTexto }}</p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Área 2: Calendario -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <button id="btn-mes-anterior" type="button" class="btn btn-sm btn-outline-secondary" aria-label="Mes anterior">
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <h4 id="mes-calendario-titulo" class="mb-0 fw-bold">{{ $mesCalendarioTitulo }}</h4>

                        <button id="btn-mes-siguiente" type="button" class="btn btn-sm btn-outline-secondary" aria-label="Mes siguiente">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>

                    <div class="border rounded d-flex align-items-center justify-content-center flex-grow-1" style="min-height: 280px;">
                        <canvas id="canvas-calendario" width="605" height="480"></canvas>
                    </div>

                    <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                        <span class="fw-semibold">Tu asistencia</span>
                        <i class="bi bi-check-lg text-success"></i>
                        <span>Total de asistencias este mes: <b id="asistencias-mes-total">{{ $asistenciasMesTotal }}</b></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>  
    <script id="kiosko-cal-data" type="application/json">@json($kioskoCalData)</script>
    @vite(['resources/js/kiosko-inicio.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataElement = document.getElementById('kiosko-cal-data');
            const data = dataElement ? JSON.parse(dataElement.textContent) : null;
            const btnMesAnterior = document.getElementById('btn-mes-anterior');
            const btnMesSiguiente = document.getElementById('btn-mes-siguiente');
            const tituloMesElement = document.getElementById('mes-calendario-titulo');
            const asistenciasMesElement = document.getElementById('asistencias-mes-total');

            const calendarioDataUrl = @json(route('dashboard.socia.calendario-data'));

            let mesVista = data?.mes ?? null;
            let anioVista = data?.año ?? null;
            let calendarioDataActual = data;

            function renderizarCalendario() {
                if (!calendarioDataActual) {
                    return;
                }

                window.kiosko.ajustarResolucionCanvas('canvas-calendario');
                window.kiosko.generarCalendarioCanvas('canvas-calendario', calendarioDataActual);
            }

            function normalizarMesAnio(mes, anio) {
                if (mes < 1) {
                    return { mes: 12, anio: anio - 1 };
                }

                if (mes > 12) {
                    return { mes: 1, anio: anio + 1 };
                }

                return { mes, anio };
            }

            function actualizarCalendarioEnPantalla(payload) {
                if (!payload?.kioskoCalData) {
                    return;
                }

                calendarioDataActual = payload.kioskoCalData;
                renderizarCalendario();

                if (tituloMesElement && payload.mesCalendarioTitulo) {
                    tituloMesElement.textContent = payload.mesCalendarioTitulo;
                }

                if (asistenciasMesElement && typeof payload.asistenciasMesTotal !== 'undefined') {
                    asistenciasMesElement.textContent = payload.asistenciasMesTotal;
                }

                mesVista = payload.kioskoCalData.mes;
                anioVista = payload.kioskoCalData.año;
            }

            async function cargarMes(delta) {
                if (!mesVista || !anioVista) {
                    return;
                }

                const siguiente = normalizarMesAnio(mesVista + delta, anioVista);
                const url = new URL(calendarioDataUrl, window.location.origin);
                url.searchParams.set('mes', String(siguiente.mes));
                url.searchParams.set('año', String(siguiente.anio));

                btnMesAnterior?.setAttribute('disabled', 'disabled');
                btnMesSiguiente?.setAttribute('disabled', 'disabled');

                try {
                    const response = await fetch(url.toString(), {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const payload = await response.json();

                    if (!response.ok || payload.error) {
                        throw new Error(payload.error || 'No se pudo cargar el calendario.');
                    }

                    actualizarCalendarioEnPantalla(payload);
                } catch (error) {
                    if (window.Swal) {
                        window.Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: error.message || 'Error al cargar calendario',
                            showConfirmButton: false,
                            showCloseButton: true,
                        });
                    }
                } finally {
                    btnMesAnterior?.removeAttribute('disabled');
                    btnMesSiguiente?.removeAttribute('disabled');
                }
            }

            if (data) {
                renderizarCalendario();
            }

            let resizeTimer;
            window.addEventListener('resize', function () {
                window.clearTimeout(resizeTimer);
                resizeTimer = window.setTimeout(function () {
                    renderizarCalendario();
                }, 120);
            });

            btnMesAnterior?.addEventListener('click', function () {
                cargarMes(-1);
            });

            btnMesSiguiente?.addEventListener('click', function () {
                cargarMes(1);
            });
        });
    </script>
</x-app-layout>
