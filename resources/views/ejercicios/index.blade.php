<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Gestión de Ejercicios</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('ejercicios.index') }}" class="link-underline-opacity-0 link-body-emphasis">Rutinas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ejercicios.index') }}" class="link-underline-opacity-0 link-body-emphasis">Ejercicios</a></li>
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

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('ejercicios.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Ejercicio
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Color</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($ejercicios as $ejercicio)
                    <tr>
                        <td>{{ $ejercicio->nombre }}</td>
                        <td>{{ Str::limit($ejercicio->descripcion, 60, '...') ?: 'Sin descripción' }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" value="{{ $ejercicio->color }}" disabled>
                                <span>{{ $ejercicio->color }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de ejercicio">
                                <a href="{{ route('ejercicios.edit', $ejercicio) }}" class="btn btn-outline-secondary" title="Editar">
                                    <x-lapiz-editar />
                                </a>

                                <button
                                    type="button"
                                    class="btn btn-outline-danger js-delete-ejercicio"
                                    data-name="{{ $ejercicio->nombre }}"
                                    data-url="{{ route('ejercicios.destroy', $ejercicio) }}"
                                    title="Eliminar"
                                >
                                    <x-bote-eliminar />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-secondary mb-0 text-center" role="alert">
                                No hay ejercicios registrados aún.
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
            document.querySelectorAll('.js-delete-ejercicio').forEach(function (button) {
                button.addEventListener('click', function () {
                    const name = this.dataset.name;
                    const url = this.dataset.url;

                    window.Swal.fire({
                        title: 'Confirmar eliminación',
                        text: `¿Está seguro de que desea eliminar el ejercicio ${name}?`,
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
