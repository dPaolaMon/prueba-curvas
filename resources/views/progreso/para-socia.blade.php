<x-app-layout>
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
    <!-- ÁREAS 2x2 (desktop) / vertical (mobile) -->
    <div class="row g-2">
        <!-- Área 1: Canvas dinámico -->
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

        <!-- Área 2: Tablas informativas -->
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
  </div>  
    <script id="kiosko-silueta-data" type="application/json">
        @json(['anterior' => $canvasMedidasAnterior, 'actual' => $canvasMedidasActual])
    </script>
    @vite(['resources/js/kiosko-inicio.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
</x-app-layout>
