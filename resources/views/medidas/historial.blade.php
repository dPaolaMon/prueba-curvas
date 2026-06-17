<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Historial de Medidas</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('medidas.index') }}" class="link-underline-opacity-0 link-body-emphasis">Gestión de Medidas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('medidas.index') }}" class="link-underline-opacity-0 link-body-emphasis">Listado</a></li>
            <li class="breadcrumb-item">Historial</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
            <div>
                <h3 class="h4 mb-1">{{ $socia->nombre }} {{ $socia->apellidos }}</h3>
                <p class="text-body-secondary mb-0">Socia #{{ $socia->num_socia }}</p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('medidas.create', ['socia_id' => $socia->id, 'return_to' => request()->fullUrl()]) }}" class="btn btn-primary">Nueva Medida</a>
                @if($medidas->isNotEmpty())
                    <a href="{{ route('medidas.historial.export', $socia) }}" class="btn btn-outline-secondary">Exportar CSV</a>
                @endif
                <a href="{{ route('medidas.index') }}" class="btn btn-outline-secondary">Volver al listado</a>
            </div>
        </div>

        @if($medidas->isEmpty())
            <div class="card shadow-sm">
                <div class="card-body text-center py-5 text-body-secondary">
                    <i class="bi bi-clipboard2-pulse fs-1 d-block mb-3"></i>
                    <h3 class="h5 text-body mb-2">Sin historial disponible</h3>
                    <p class="mb-0">Esta socia aún no tiene medidas registradas.</p>
                </div>
            </div>
        @else
            <div class="row g-4 mb-4">
                <div class="col-12 col-xl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h4 class="h6 text-uppercase text-body-secondary mb-3">Medición más reciente</h4>
                            <div class="fw-semibold mb-3">{{ $medidaActual?->fecha_registro?->format('d/m/Y H:i') }}</div>
                            <div class="small text-body-secondary">Usa esta medición como referencia actual para ajustar el plan de trabajo.</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h4 class="h6 text-uppercase text-body-secondary mb-3">Medición anterior</h4>
                            <div class="fw-semibold mb-3">{{ $medidaAnterior?->fecha_registro?->format('d/m/Y H:i') ?? 'Sin comparación disponible' }}</div>
                            <div class="small text-body-secondary">El comparativo inmediato ayuda a detectar avances o retrocesos recientes.</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h4 class="h6 text-uppercase text-body-secondary mb-3">Registros acumulados</h4>
                            <div class="display-6 mb-2">{{ $medidas->count() }}</div>
                            <div class="small text-body-secondary">Ordenados de la medición más reciente a la más antigua.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                        <h4 class="h5 mb-0">Comparativo reciente</h4>
                        @if(!$medidaAnterior)
                            <span class="badge text-bg-secondary">Se requiere al menos una medición previa</span>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Medida</th>
                                    <th scope="col">Actual</th>
                                    <th scope="col">Anterior</th>
                                    <th scope="col">Diferencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resumenComparativo as $fila)
                                    <tr>
                                        <td class="fw-semibold">{{ $fila['label'] }}</td>
                                        <td>
                                            @if(is_null($fila['actual']))
                                                —
                                            @else
                                                {{ number_format((float) $fila['actual'], 2) }}{{ $fila['unidad'] !== '' ? ' ' . $fila['unidad'] : '' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(is_null($fila['anterior']))
                                                —
                                            @else
                                                {{ number_format((float) $fila['anterior'], 2) }}{{ $fila['unidad'] !== '' ? ' ' . $fila['unidad'] : '' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(is_null($fila['diferencia']))
                                                <span class="text-body-secondary">—</span>
                                            @else
                                                @php
                                                    $diferencia = (float) $fila['diferencia'];
                                                    $clase = $diferencia < 0 ? 'text-success' : ($diferencia > 0 ? 'text-danger' : 'text-body-secondary');
                                                @endphp
                                                <span class="fw-semibold {{ $clase }}">
                                                    {{ $diferencia > 0 ? '+' : '' }}{{ number_format($diferencia, 2) }}{{ $fila['unidad'] !== '' ? ' ' . $fila['unidad'] : '' }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                        <h4 class="h5 mb-0">Historial completo</h4>
                        <span class="text-body-secondary small">De más reciente a más antiguo</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Fecha</th>
                                    @foreach($camposMedidas as $meta)
                                        <th scope="col">{{ $meta['label'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medidas as $medida)
                                    <tr>
                                        <td class="fw-semibold text-nowrap">{{ $medida->fecha_registro?->format('d/m/Y H:i') }}</td>
                                        @foreach($camposMedidas as $campo => $meta)
                                            <td>
                                                @php
                                                    $valor = $medida->{$campo};
                                                @endphp
                                                {{ is_null($valor) ? '—' : number_format((float) $valor, 2) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>