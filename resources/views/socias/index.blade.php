<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Listado de Socias') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Socias') }}</a></li>            
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Listado') }}</a></li>
        </ol>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    setTimeout(function () {
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
                    }, 0);
                });
            </script>
        @endif
        
        <form method="GET" action="{{ route('socias.index') }}" class="row g-2 align-items-end mb-3 justify-content-end">
            <div class="col-12 col-md-6 col-lg-5">
                <label for="search" class="form-label">Buscar socia</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Buscar por número, nombre o apellidos"
                    class="form-control"
                >
            </div>

            <div class="col-12 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-secondary">Buscar</button>

                @if(!empty($search))
                    <a href="{{ route('socias.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif

                <a href="{{ route('socias.create') }}" class="btn btn-primary">Registro Nueva Socia</a>

            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">
                            @php
                                $numDirection = (($sort ?? 'num_socia') === 'num_socia' && ($direction ?? 'asc') === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('socias.index', array_merge(request()->query(), ['sort' => 'num_socia', 'direction' => $numDirection])) }}" class="link-body-emphasis text-decoration-none">
                                <span>No. Socia</span>
                                @if(($sort ?? 'num_socia') === 'num_socia')
                                    <span class="ms-1">{{ ($direction ?? 'asc') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">
                            @php
                                $nombreDirection = (($sort ?? '') === 'nombre' && ($direction ?? 'asc') === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('socias.index', array_merge(request()->query(), ['sort' => 'nombre', 'direction' => $nombreDirection])) }}" class="link-body-emphasis text-decoration-none">
                                <span>Nombre</span>
                                @if(($sort ?? '') === 'nombre')
                                    <span class="ms-1">{{ ($direction ?? 'asc') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">Celular</th>
                        <th scope="col">Email</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">
                            @php
                                $fechaDirection = (($sort ?? '') === 'fecha_alta' && ($direction ?? 'asc') === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('socias.index', array_merge(request()->query(), ['sort' => 'fecha_alta', 'direction' => $fechaDirection])) }}" class="link-body-emphasis text-decoration-none">
                                <span>Fecha Alta</span>
                                @if(($sort ?? '') === 'fecha_alta')
                                    <span class="ms-1">{{ ($direction ?? 'asc') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">
                            @php
                                $reingresoDirection = (($sort ?? '') === 'fecha_reingreso' && ($direction ?? 'asc') === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('socias.index', array_merge(request()->query(), ['sort' => 'fecha_reingreso', 'direction' => $reingresoDirection])) }}" class="link-body-emphasis text-decoration-none">
                                <span>Fecha Reingreso</span>
                                @if(($sort ?? '') === 'fecha_reingreso')
                                    <span class="ms-1">{{ ($direction ?? 'asc') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">Foto</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($socias as $socia)
                    <tr>
                        <td>{{ $socia->num_socia }}</td>
                        <td>{{ $socia->nombre }} {{ $socia->apellidos }}</td>
                        <td>{{ $socia->celular }}</td>
                        <td>{{ $socia->email }}</td>
                        <td>{{ $socia->municipio->nombre ?? '—' }}</td>
                        <td>{{ $socia->estado->nombre ?? '—' }}</td>
                        <td>
                            <button
                                type="button"
                                class="badge border-0 js-toggle-estatus {{ $socia->estatus === 'Activa' ? 'text-bg-success' : 'text-bg-danger' }}"
                                data-name="{{ $socia->nombre }} {{ $socia->apellidos }}"
                                data-estatus="{{ $socia->estatus }}"
                                data-url="{{ route('socias.toggle-estatus', $socia) }}"
                                title="Cambiar estatus"
                            >
                                {{ $socia->estatus }}
                            </button>
                        </td>
                        <td>{{ $socia->fecha_alta?->format('d/m/Y') }}</td>
                        <td>{{ $socia->fecha_reingreso?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if($socia->foto)
                                <img src="{{ asset('storage/' . $socia->foto) }}"
                                    alt="Foto de {{ $socia->nombre }}"
                                    class="rounded-circle"
                                    width="40"
                                    height="40">
                            @else
                                <span class="text-body-secondary">Sin foto</span>
                            @endif
                        </td>

                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de socia">
                                <a href="{{ route('socias.show', $socia) }}" class="btn btn-outline-primary" title="Ver detalles">
                                    <x-ojito-ver />
                                </a>

                                <a href="{{ route('medidas.create', ['socia_id' => $socia->id]) }}" class="btn btn-outline-info" title="Registrar medida" aria-label="Registrar medida">
                                    <x-regla-medida />
                                </a>

                                <a href="{{ route('socias.edit', $socia) }}" class="btn btn-outline-secondary" title="Editar">
                                    <x-lapiz-editar />
                                </a>

                                <button
                                    type="button"
                                    class="btn btn-outline-danger js-delete-socia"
                                    data-name="{{ $socia->nombre }} {{ $socia->apellidos }}"
                                    data-url="{{ route('socias.destroy', $socia) }}"
                                    title="Eliminar"
                                >
                                    <x-bote-eliminar />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            <div class="alert alert-secondary mb-0 text-center" role="alert">
                                No hay socias registradas aún.
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $socias->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-toggle-estatus').forEach(function (button) {
                button.addEventListener('click', function () {
                    const name = this.dataset.name;
                    const estatus = this.dataset.estatus;
                    const url = this.dataset.url;

                    const isActiva = estatus === 'Activa';
                    const text = isActiva
                        ? 'El estatus de la socia es Activa, ¿quieres darla de baja y ocultarla en el resto del sistema, así como restringir su acceso?'
                        : 'El estatus de la socia es Baja, ¿quieres reactivarla y mostrarla en el resto del sistema, así como habilitar su acceso?';

                    window.Swal.fire({
                        title: `Cambiar estatus de ${name}`,
                        text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: isActiva ? 'Sí, dar de baja' : 'Sí, reactivar',
                        confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            return;
                        }

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'PATCH';

                        form.appendChild(csrf);
                        form.appendChild(method);
                        document.body.appendChild(form);
                        form.submit();
                    });
                });
            });

            document.querySelectorAll('.js-delete-socia').forEach(function (button) {
                button.addEventListener('click', function () {
                    const name = this.dataset.name;
                    const url = this.dataset.url;

                    window.Swal.fire({
                        title: 'Confirmar eliminación',
                        text: `¿Está seguro de que desea eliminar a ${name}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            return;
                        }

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';

                        form.appendChild(csrf);
                        form.appendChild(method);
                        document.body.appendChild(form);
                        form.submit();
                    });
                });
            });
        });
    </script>
</x-app-layout>
