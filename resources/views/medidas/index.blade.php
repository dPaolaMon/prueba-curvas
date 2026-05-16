<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Gestión de Medidas</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('medidas.index') }}" class="link-underline-opacity-0 link-body-emphasis">Socias</a></li>
            <li class="breadcrumb-item"><a href="{{ route('medidas.index') }}" class="link-underline-opacity-0 link-body-emphasis">Medidas</a></li>
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
            <a href="{{ route('medidas.create') }}" class="btn btn-primary">Nueva Medida</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Socia</th>
                        <th scope="col">
                            @php
                                $fechaDirection = (($sort ?? 'fecha_registro') === 'fecha_registro' && ($direction ?? 'desc') === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('medidas.index', array_merge(request()->query(), ['sort' => 'fecha_registro', 'direction' => $fechaDirection])) }}" class="link-body-emphasis text-decoration-none">
                                <span>Fecha registro</span>
                                @if(($sort ?? 'fecha_registro') === 'fecha_registro')
                                    <span class="ms-1">{{ ($direction ?? 'desc') === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th scope="col">Busto</th>
                        <th scope="col">Cintura</th>
                        <th scope="col">Abdomen</th>
                        <th scope="col">Caderas</th>
                        <th scope="col">Muslo</th>
                        <th scope="col">Brazo</th>
                        <th scope="col">Peso</th>
                        <th scope="col">Altura</th>
                        <th scope="col">% Grasa</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($medidas as $medida)
                    <tr>
                        <td>{{ $medida->socia?->nombre }} {{ $medida->socia?->apellidos }}</td>
                        <td>{{ $medida->fecha_registro?->format('d/m/Y H:i') }}</td>
                        <td>{{ $medida->busto }}</td>
                        <td>{{ $medida->cintura }}</td>
                        <td>{{ $medida->abdomen }}</td>
                        <td>{{ $medida->caderas }}</td>
                        <td>{{ $medida->muslo }}</td>
                        <td>{{ $medida->brazo }}</td>
                        <td>{{ $medida->peso }}</td>
                        <td>{{ $medida->altura }}</td>
                        <td>{{ $medida->porcentaje_grasa }}</td>

                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de medida">
                                <a href="{{ route('medidas.edit', $medida) }}" class="btn btn-outline-secondary" title="Editar">
                                    <x-lapiz-editar />
                                </a>

                                <button
                                    type="button"
                                    class="btn btn-outline-danger js-delete-medida"
                                    data-name="{{ $medida->socia?->nombre }} {{ $medida->socia?->apellidos }}"
                                    data-url="{{ route('medidas.destroy', $medida) }}"
                                    title="Eliminar"
                                >
                                    <x-bote-eliminar />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">
                            <div class="alert alert-secondary mb-0 text-center" role="alert">
                                No hay medidas registradas aún.
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
            document.querySelectorAll('.js-delete-medida').forEach(function (button) {
                button.addEventListener('click', function () {
                    const name = this.dataset.name;
                    const url = this.dataset.url;

                    window.Swal.fire({
                        title: 'Confirmar eliminación',
                        text: `¿Está seguro de que desea eliminar la medida de ${name}?`,
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
