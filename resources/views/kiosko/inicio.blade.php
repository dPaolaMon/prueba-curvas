<x-subapp-layout>
    @if(!empty($toastAsistencia))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!window.Swal) return;

                window.Swal.fire({
                    toast: true,
                    theme: 'auto',
                    position: 'top-end',
                    icon: @js($toastAsistencia['icon']),
                    title: @js($toastAsistencia['message']),
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    <!-- HEADER -->
    <div class="row dashboard-header">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                @if(!empty($socia->foto))
                    <img
                        src="{{ asset('storage/' . $socia->foto) }}"
                        alt="Foto de {{ $nombre_socia }}"
                        class="rounded-circle img-thumbnail"
                        width="84"
                        height="84"
                        style="object-fit: cover;"
                    >
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                         style="width:84px; height:84px;">
                        <i class="bi bi-person-fill text-white" style="font-size: 2.2rem;"></i>
                    </div>
                @endif

                <div>
                    <h3 class="fw-bold mb-1">
                        ¡Hola, {{ $nombre_socia }}! 💜
                    </h3>

                    <p class="text-muted mb-0">
                        Bienvenida a tu espacio. Aquí puedes revisar tu membresía, pagos y reservar clases.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 status-box">
            <div class="text-muted">
                Estado
            </div>
            <div class="{{ $socia->estatus === 'Activa' ? 'status-active' : 'text-danger fw-semibold' }}">
                {{ $socia->estatus }}
            </div>
            <div class="text-muted">
                @if($proximoPago)
                    Vigente hasta {{ $proximoPago->format('Y-m-d') }}
                @else
                    Vigencia no disponible
                @endif
            </div>
        </div>
    </div>

    <!-- ÁREAS 2x2 (desktop) / vertical (mobile) -->
    <div class="row g-4">
        <!-- Área 1: Notificaciones con scroll -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Notificaciones</h5>

                    <div class="d-flex flex-column gap-3 overflow-auto pe-1" style="max-height: 420px;">
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
                                        <strong>
                                            Mensaje de {{ $remitente }}
                                        </strong>
                                    </div>
                                    <p class="mb-1 text-muted">{{ $textoMensaje !== '' ? $textoMensaje : '(sin contenido)' }}</p>
                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                        <small class="text-body-secondary">Enviado: {{ $mensaje->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</small>
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
                        <canvas id="canvas-calendario" width="605" height="380"></canvas>
                    </div>

                    <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                        <span class="fw-semibold">Tu asistencia</span>
                        <i class="bi bi-check-lg text-success"></i>
                        <span>Total de asistencias este mes: <b id="asistencias-mes-total">{{ $asistenciasMesTotal }}</b></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Área 3: Canvas dinámico -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Progreso corporal</h5>
                    <div class="border rounded d-flex align-items-center justify-content-center" style="min-height: 420px;">
                        @if($medidaActual)
                            <canvas id="canvas-silueta" width="605" height="602"></canvas>
                        @else
                            <div class="text-center text-muted px-4">
                                Aún no hay medidas registradas para mostrar progreso corporal.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Área 4: Tablas informativas -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="fw-bold mb-3 text-danger">{{ $mensajeProgreso }}</h3>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                Última medición:
                                                {{ $medidaActual?->fecha_registro?->format('d/m/Y') ?? 'Sin registros' }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resumenMedidas as $fila)
                                            <tr>
                                                <td>
                                                    {{ $fila['label'] }}:
                                                    {{ is_null($fila['actual']) ? '—' : number_format((float) $fila['actual'], 2) . ' ' . $fila['unidad'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                Medición anterior:
                                                {{ $medidaAnterior?->fecha_registro?->format('d/m/Y') ?? 'Sin comparación' }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resumenMedidas as $fila)
                                            <tr>
                                                <td>
                                                    {{ $fila['label'] }}:
                                                    {{ is_null($fila['anterior']) ? '—' : number_format((float) $fila['anterior'], 2) . ' ' . $fila['unidad'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Comparativo (actual - anterior)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resumenMedidas as $fila)
                                            <tr>
                                                <td>
                                                    {{ $fila['label'] }}:
                                                    @if(is_null($fila['diferencia']))
                                                        —
                                                    @else
                                                        {{ $fila['diferencia'] > 0 ? '+' : '' }}{{ number_format((float) $fila['diferencia'], 2) }} {{ $fila['unidad'] }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script id="kiosko-cal-data" type="application/json">@json($kioskoCalData)</script>
    <script id="kiosko-silueta-data" type="application/json">
        @json(['anterior' => $canvasMedidasAnterior, 'actual' => $canvasMedidasActual])
    </script>
    @vite(['resources/js/kiosko-inicio.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataElement = document.getElementById('kiosko-cal-data');
            const data = dataElement ? JSON.parse(dataElement.textContent) : null;
            const btnMesAnterior = document.getElementById('btn-mes-anterior');
            const btnMesSiguiente = document.getElementById('btn-mes-siguiente');
            const tituloMesElement = document.getElementById('mes-calendario-titulo');
            const asistenciasMesElement = document.getElementById('asistencias-mes-total');

            const calendarioDataUrl = @json(route('kiosko.calendario-data'));
            const tokenKiosko = @json($token);
            const numSociaKiosko = @json((int) $num_socia);

            let mesVista = data?.mes ?? null;
            let anioVista = data?.año ?? null;

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

                window.kiosko.generarCalendarioCanvas('canvas-calendario', payload.kioskoCalData);

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
                if (!tokenKiosko || !mesVista || !anioVista) {
                    return;
                }

                const siguiente = normalizarMesAnio(mesVista + delta, anioVista);
                const url = new URL(calendarioDataUrl, window.location.origin);
                url.searchParams.set('token', tokenKiosko);
                url.searchParams.set('num_socia', String(numSociaKiosko));
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
                            timer: 2200,
                        });
                    }
                } finally {
                    btnMesAnterior?.removeAttribute('disabled');
                    btnMesSiguiente?.removeAttribute('disabled');
                }
            }

            if (data) {
                window.kiosko.ajustarResolucionCanvas('canvas-calendario');
                window.kiosko.generarCalendarioCanvas('canvas-calendario', data);
            }

            btnMesAnterior?.addEventListener('click', function () {
                cargarMes(-1);
            });

            btnMesSiguiente?.addEventListener('click', function () {
                cargarMes(1);
            });

            const siluetaDataElement = document.getElementById('kiosko-silueta-data');
            const siluetaData = siluetaDataElement ? JSON.parse(siluetaDataElement.textContent) : null;

            let lienzo = document.getElementById("canvas-silueta");

            if (!lienzo) {
                return;
            }

            let contexto = lienzo.getContext("2d");

            const medidasAntData = siluetaData?.anterior ?? { brazos: 0, busto: 0, cintura: 0, abdomen: 0, cadera: 0, muslos: 0, papada: 0 };
            const medidasActData = siluetaData?.actual ?? { brazos: 26, busto: 83, cintura: 60, abdomen: 58, cadera: 78, muslos: 41, papada: 0 };

            let medidasAnt = new window.kiosko.Medidas(
                medidasAntData.brazos,
                medidasAntData.busto,
                medidasAntData.cintura,
                medidasAntData.abdomen,
                medidasAntData.cadera,
                medidasAntData.muslos,
                medidasAntData.papada
            );

            let medidasAct = new window.kiosko.Medidas(
                medidasActData.brazos,
                medidasActData.busto,
                medidasActData.cintura,
                medidasActData.abdomen,
                medidasActData.cadera,
                medidasActData.muslos,
                medidasActData.papada
            );


            
            window.kiosko.dibujaMujer(contexto, 2, medidasAnt, medidasAct);
            
        });
    </script>
</x-subapp-layout>