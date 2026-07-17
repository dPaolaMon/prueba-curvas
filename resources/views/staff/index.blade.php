<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Catálogo de Usuarios</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}" class="link-underline-opacity-0 link-body-emphasis">Gestión Staff</a></li>
            <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}" class="link-underline-opacity-0 link-body-emphasis">Listado</a></li>
        </ol>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (!window.Swal) return;

                    window.Swal.fire({
                        toast: true,
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
                        position: 'top-end',
                        icon: 'error',
                        title: @js(session('error')),
                        showConfirmButton: false,
                        showCloseButton: true,
                    });
                });
            </script>
        @endif

        <form method="GET" action="{{ route('usuarios.index') }}" class="row g-2 align-items-end mb-3 justify-content-end">
            <div class="col-12 col-md-6 col-lg-5">
                <label for="search" class="form-label">Buscar usuario</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Buscar por nombre, username, correo o rol"
                    class="form-control"
                >
            </div>

            <div class="col-12 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-secondary">
                    <i class="bi bi-search me-2"></i>Buscar
                </button>
                @if(!empty($search))
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                    </a>
                @endif
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Agregar Usuario
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Username</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->username }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge text-bg-secondary">{{ $usuario->role }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $usuario->suspendido ? 'text-bg-danger' : 'text-bg-success' }}">
                                    {{ $usuario->suspendido ? 'Suspendido' : 'Activo' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de usuario">
                                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-outline-secondary" title="Editar">
                                        <x-lapiz-editar />
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger js-delete-usuario"
                                        data-name="{{ $usuario->name }}"
                                        data-url="{{ route('usuarios.destroy', $usuario) }}"
                                        title="Eliminar"
                                    >
                                        <x-bote-eliminar />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="alert alert-secondary mb-0 text-center" role="alert">
                                    No hay usuarios registrados aún.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $usuarios->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-delete-usuario').forEach(function (button) {
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
