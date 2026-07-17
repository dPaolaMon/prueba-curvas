<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Plan: {{ $planVersion->plan->nombre }} | Versión: {{ $planVersion->nombre_comercial }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">Administración</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">Planes</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planes.show', $planVersion->plan) }}" class="link-underline-opacity-0 link-body-emphasis">{{ $planVersion->plan->nombre }}</a></li>
            <li class="breadcrumb-item">{{ $planVersion->nombre_comercial }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (!window.Swal) return;

                    window.Swal.fire({
                        toast: true,
                        theme: 'auto',
                        position: 'top-end',
                        icon: 'success',
                        title: @js(session('success')),
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true,
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (!window.Swal) return;

                    window.Swal.fire({
                        toast: true,
                        theme: 'auto',
                        position: 'top-end',
                        icon: 'error',
                        title: @js(session('error')),
                        showConfirmButton: false,
                        showCloseButton: true,
                    });
                });
            </script>
        @endif

        <!-- Botones de acción generales -->
        <div class="row g-2 align-items-end mb-3 justify-content-end">
          <div class="col-12 col-md-auto d-flex gap-2">
                        <a href="{{ $backUrl ?? route('planes.show', $planVersion->plan) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Regresar
            </a>
            @if($planVersion->estado_publicacion === 'borrador')
                <a href="{{ route('planes-versiones.edit', $planVersion) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                <form action="{{ route('planes-versiones.publish', $planVersion) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">
                        <i class="bi bi-check-circle me-2"></i>Publicar
                    </button>
                </form>
            @endif
          </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8">
                {{-- Información General --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Información General</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Plan:</div>
                        <div class="col-sm-9"><strong>{{ $planVersion->plan->nombre }}</strong></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Vigencia:</div>
                        <div class="col-sm-9">
                            <strong>{{ $planVersion->vigencia_desde->format('d/m/Y') }}</strong>
                            @if($planVersion->vigencia_hasta)
                                a <strong>{{ $planVersion->vigencia_hasta->format('d/m/Y') }}</strong>
                            @else
                                - <strong>Sin fecha fin (Vigente)</strong>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Estado:</div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $planVersion->estado_publicacion === 'publicado' ? 'success' : 'warning' }}">
                                {{ ucfirst($planVersion->estado_publicacion) }}
                            </span>
                        </div>
                    </div>

                    @if($planVersion->notas)
                        <div class="row">
                            <div class="col-sm-3 text-muted">Notas:</div>
                            <div class="col-sm-9">{{ $planVersion->notas }}</div>
                        </div>
                    @endif
                </div>

                {{-- Estructura de Precios --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Estructura de Precios</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3 text-center border-end">
                            <small class="text-muted d-block">Inscripción</small>
                            <h4 class="mb-0">${{ number_format($planVersion->precio_inscripcion, 2) }}</h4>
                        </div>
                        <div class="col-md-4 mb-3 text-center border-end">
                            <small class="text-muted d-block">Mensualidad</small>
                            <h4 class="mb-0">${{ number_format($planVersion->precio_mensualidad, 2) }}</h4>
                        </div>
                        <div class="col-md-4 mb-3 text-center">
                            <small class="text-muted d-block">Pago Único</small>
                            <h4 class="mb-0">{{ $planVersion->precio_pago_unico ? '$' . number_format($planVersion->precio_pago_unico, 2) : 'N/A' }}</h4>
                        </div>
                    </div>

                    @if($planVersion->precio_mensualidad_recurrente)
                        <div class="alert alert-info">
                            <small><strong>Mensualidad Recurrente:</strong> ${{ number_format($planVersion->precio_mensualidad_recurrente, 2) }}</small>
                        </div>
                    @endif
                </div>

                {{-- Duración y Cobranza --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Duración y Cobranza</h5>
                    
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <small class="text-muted">Duración</small>
                            <div class="h5 mb-0">{{ $planVersion->meses_duracion }} meses</div>
                        </div>
                        <div class="col-md-4 text-center border-start border-end">
                            <small class="text-muted">Cobrables</small>
                            <div class="h5 mb-0">{{ $planVersion->meses_cobrables }} meses</div>
                        </div>
                        <div class="col-md-4 text-center">
                            <small class="text-muted">Gratis</small>
                            <div class="h5 mb-0">{{ $planVersion->meses_gratis }} meses</div>
                        </div>
                    </div>
                </div>

                {{-- Comisiones y Retenciones --}}
                <div class="bg-white p-4 rounded shadow">
                    <h5 class="mb-4">Comisiones y Retenciones</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Comisión</small>
                            <div class="h4 mb-0">${{ number_format($planVersion->comision_monto, 2) }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Retención</small>
                            <div class="h4 mb-0">${{ number_format($planVersion->retencion_monto, 2) }}</div>
                        </div>
                    </div>

                    @if($planVersion->retencion_mes_numero)
                        <div class="alert alert-warning">
                            <small><i class="bi bi-exclamation-triangle me-2"></i>Retención aplica en mes: <strong>{{ $planVersion->retencion_mes_numero }}</strong></small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Panel Lateral --}}
            <div class="col-12 col-lg-4">
                <div class="bg-light p-4 rounded mb-4">
                    <h6 class="mb-3">Estadísticas</h6>
                    <div class="mb-3">
                        <small class="text-muted">Membresías</small>
                        <div class="h4 mb-0">{{ $planVersion->membresias()->count() }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Pagos Registrados</small>
                        <div class="h4 mb-0">{{ $planVersion->pagos()->count() }}</div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded mb-4">
                    <h6 class="mb-3">Historial</h6>
                    <small class="d-block text-muted mb-2">Creado: {{ $planVersion->created_at->format('d/m/Y H:i') }}</small>
                    <small class="d-block text-muted">Por: {{ $planVersion->creadoPor?->name ?? 'N/A' }}</small>

                    @if($planVersion->actualizado_por)
                        <hr>
                        <small class="d-block text-muted mb-2">Actualizado: {{ $planVersion->updated_at->format('d/m/Y H:i') }}</small>
                        <small class="d-block text-muted">Por: {{ $planVersion->actualizadoPor?->name ?? 'N/A' }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
