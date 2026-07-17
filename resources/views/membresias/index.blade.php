<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Membresías') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Socias') }}</a></li> 
            <li class="breadcrumb-item">Membresías</li>
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
            <a href="{{ route('membresias.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Asociar Membresía a Socia
            </a>
          </div>
        </div>

        <div class="bg-white p-4 rounded shadow">
            {{-- Búsqueda --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('membresias.index') }}" class="row g-3">
                    <div class="col-12 col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por socia, nombre o número..."
                               value="{{ $search }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <select name="estatus" class="form-select">
                            <option value="todos">Todos los estatus</option>
                            @foreach($estatusOpciones as $opcion)
                                <option value="{{ $opcion['value'] }}" @selected($estatus === $opcion['value'])>
                                    {{ $opcion['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-search me-2"></i>Buscar
                        </button>
                        <a href="{{ route('membresias.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                        </a>
                    </div>
                </form>
            </div>

            @if($membresias->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Socia</th>
                                <th>Plan</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estatus</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($membresias as $membresia)
                                <tr>
                                    <td>
                                        <strong>{{ $membresia->socia->nombre }} {{ $membresia->socia->apellidos }}</strong><br>
                                        <small class="text-muted">#{{ $membresia->socia->num_socia }}</small>
                                    </td>
                                    <td>{{ $membresia->planVersion->plan->nombre }}</td>
                                    <td>{{ $membresia->fecha_inicio->format('d/m/Y') }}</td>
                                    <td>{{ $membresia->fecha_fin_programada->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ match($membresia->estatus) { 'activa' => 'success', 'pausada' => 'warning', 'cancelada' => 'danger', 'vencida' => 'secondary', default => 'info' } }}">
                                            {{ ucfirst($membresia->estatus) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de membresía">
                                            <a href="{{ route('membresias.show', $membresia) }}" class="btn btn-outline-primary" title="Ver detalles">
                                                <x-ojito-ver />
                                            </a>
                                            <a href="{{ route('membresias.edit', $membresia) }}" class="btn btn-outline-secondary" title="Editar">
                                                <x-lapiz-editar />
                                            </a>
                                            <button type="submit" form="delete-membresia-{{ $membresia->id }}" class="btn btn-outline-danger" title="Eliminar">
                                                <x-bote-eliminar />
                                            </button>
                                        </div>
                                        <form id="delete-membresia-{{ $membresia->id }}" action="{{ route('membresias.destroy', $membresia) }}" method="POST" class="d-none js-delete-membresia-form" data-membresia="{{ $membresia->nombre }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $membresias->links() }}
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <p class="mb-0"><i class="bi bi-info-circle me-2"></i>No hay membresías registradas.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nativeSubmit = HTMLFormElement.prototype.submit;

            document.querySelectorAll('form.js-delete-membresia-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    if (!window.Swal) return;

                    const nombreMembresia = form.dataset.membresia || 'esta membresía';

                    window.Swal.fire({
                        title: 'Eliminar membresía',
                        text: `¿Seguro que deseas eliminar ${nombreMembresia}? Esta accion no se puede deshacer.`,
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
