<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Gestión de Máquinas</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('maquinas.index') }}" class="link-underline-opacity-0 link-body-emphasis">Rutinas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('maquinas.index') }}" class="link-underline-opacity-0 link-body-emphasis">Máquinas</a></li>
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
                        timer: 2600,
                        timerProgressBar: true,
                    });
                });
            </script>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('maquinas.create') }}" class="btn btn-primary">Nueva Máquina</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($maquinas as $maquina)
                    <tr>
                        <td>{{ $maquina->nombre }}</td>
                        <td>{{ Str::limit($maquina->descripcion, 60, '...') ?: 'Sin descripción' }}</td>

                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de máquina">
                                <a href="{{ route('maquinas.edit', $maquina) }}" class="btn btn-outline-secondary" title="Editar">
                                    <x-lapiz-editar />
                                </a>

                                <button
                                    type="button"
                                    class="btn btn-outline-danger js-delete-maquina"
                                    data-name="{{ $maquina->nombre }}"
                                    data-url="{{ route('maquinas.destroy', $maquina) }}"
                                    title="Eliminar"
                                >
                                    <x-bote-eliminar />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="alert alert-secondary mb-0 text-center" role="alert">
                                No hay máquinas registradas aún.
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-delete-maquina').forEach(function (button) {
                button.addEventListener('click', function () {
                    const name = this.dataset.name;
                    const url = this.dataset.url;

                    window.Swal.fire({
                        title: 'Confirmar eliminación',
                        text: `¿Está seguro de que desea eliminar la máquina ${name}?`,
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
