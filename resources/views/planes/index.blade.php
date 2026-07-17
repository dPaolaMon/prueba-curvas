<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Planes') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Administración') }}</a></li>            
	        <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Planes') }}</a></li>
        </ol>
    </x-slot>

    <div class="container py-4">

        <!-- Alertas flash iniciales -->
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
            <a href="{{ route('planes.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Nuevo Plan</a>
          </div>
        </div>

        <div class="bg-white p-4 rounded shadow">
            {{-- Búsqueda y Filtros --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('planes.index') }}" class="row g-3">
                    <div class="col-12 col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o descripción..."
                               value="{{ $search }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <select name="estatus" class="form-select">
                            <option value="todos">Todos los estatus</option>
                            <option value="activo" @selected($estatus === 'activo')>Activo</option>
                            <option value="inactivo" @selected($estatus === 'inactivo')>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-search me-2"></i>Buscar
                        </button>
                        <a href="{{ route('planes.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabla de Planes --}}
            @if($planes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estatus</th>
                                <th>Versiones</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($planes as $plan)
                                <tr>
                                    <td class="fw-bold">{{ $plan->nombre }}</td>
                                    <td><small class="text-muted">{{ Str::limit($plan->descripcion, 40) }}</small></td>
                                    <td>
                                        <span class="badge bg-{{ $plan->estatus === 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($plan->estatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($plan->versiones->isNotEmpty())
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($plan->versiones->take(3) as $version)
                                                    <div class="d-flex align-items-center justify-content-between gap-2 border rounded px-2 py-1">
                                                        <a class="text-decoration-none small fw-semibold" href="{{ route('planes-versiones.show', $version) }}">
                                                            {{ $version->nombre_comercial ?: ('Version #' . $version->id) }}
                                                        </a>
                                                        <span class="badge bg-{{ $version->estado_publicacion === 'publicado' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($version->estado_publicacion) }}
                                                        </span>
                                                    </div>
                                                @endforeach

                                                @if($plan->versiones->count() > 3)
                                                    <a class="small text-decoration-none mt-1" href="{{ route('planes.planes-versiones.index', $plan) }}">
                                                        Ver todas ({{ $plan->versiones->count() }})
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted small">Sin versiones</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de plan">
                                            <a href="{{ route('planes.show', $plan) }}" class="btn btn-outline-primary" title="Ver detalles">
                                                <x-ojito-ver />
                                            </a>
                                            <a href="{{ route('planes.edit', $plan) }}" class="btn btn-outline-secondary" title="Editar">
                                                <x-lapiz-editar />
                                            </a>
                                            <button type="submit" form="delete-plan-{{ $plan->id }}" class="btn btn-outline-danger" title="Eliminar">
                                                <x-bote-eliminar />
                                            </button>
                                        </div>
                                        <form id="delete-plan-{{ $plan->id }}" action="{{ route('planes.destroy', $plan) }}" method="POST" class="d-none js-delete-plan-form" data-plan="{{ $plan->nombre }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $planes->links() }}
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <p class="mb-0"><i class="bi bi-info-circle me-2"></i>No hay planes registrados.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nativeSubmit = HTMLFormElement.prototype.submit;

            document.querySelectorAll('form.js-delete-plan-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    if (!window.Swal) return;

                    const nombrePlan = form.dataset.plan || 'este plan';

                    window.Swal.fire({
                        title: 'Eliminar plan',
                        text: `¿Seguro que deseas eliminar ${nombrePlan}? Esta accion no se puede deshacer.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Si, eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            nativeSubmit.call(form);
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
