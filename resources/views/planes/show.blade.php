<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Detalles del plan: {{ $plan->nombre }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Administración') }}</a></li>            
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Planes') }}</a></li>
            <li class="breadcrumb-item">{{ $plan->nombre }}</li>
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
            <a href="{{ route('planes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Planes
            </a>
            <a href="{{ route('planes.edit', $plan) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>Editar
            </a>
          </div>
        </div>

        <div class="row">
            {{-- Panel Principal --}}
            <div class="col-12 col-lg-8">
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Información General</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Nombre:</div>
                        <div class="col-sm-9">
                            <strong>{{ $plan->nombre }}</strong>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Estatus:</div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $plan->estatus === 'activo' ? 'success' : 'secondary' }}">
                                {{ ucfirst($plan->estatus) }}
                            </span>
                        </div>
                    </div>

                    @if($plan->descripcion)
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Descripción:</div>
                            <div class="col-sm-9">
                                {{ $plan->descripcion }}
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Creado por:</div>
                        <div class="col-sm-9">
                            {{ $plan->creadoPor?->name ?? 'N/A' }} - {{ $plan->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    @if($plan->actualizado_por)
                        <div class="row">
                            <div class="col-sm-3 text-muted">Actualizado por:</div>
                            <div class="col-sm-9">
                                {{ $plan->actualizadoPor?->name ?? 'N/A' }} - {{ $plan->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Versiones del Plan --}}
                <div class="bg-white p-4 rounded shadow">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Versiones del Plan</h5>
                        <a href="{{ route('planes.planes-versiones.index', $plan) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-list me-2"></i>Versiones
                        </a>
                    </div>

                    @if($plan->versiones->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre Comercial</th>
                                        <th>Vigencia</th>
                                        <th>Publicación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plan->versiones as $version)
                                        <tr>
                                            <td>{{ $version->nombre_comercial }}</td>
                                            <td>
                                                <small>
                                                    {{ $version->vigencia_desde->format('d/m/Y') }}
                                                    @if($version->vigencia_hasta)
                                                        - {{ $version->vigencia_hasta->format('d/m/Y') }}
                                                    @else
                                                        - <strong>Vigente</strong>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $version->estado_publicacion === 'publicado' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($version->estado_publicacion) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <small>No hay versiones registradas para este plan.</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Panel Lateral --}}
            <div class="col-12 col-lg-4">
                <div class="bg-light p-4 rounded mb-4">
                    <h6 class="mb-3">Estadísticas</h6>
                    <div class="mb-3">
                        <small class="text-muted">Total de Versiones</small>
                        <div class="h4 mb-0">{{ $plan->versiones()->count() }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Versiones Publicadas</small>
                        <div class="h4 mb-0">{{ $plan->versiones()->where('estado_publicacion', 'publicado')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
