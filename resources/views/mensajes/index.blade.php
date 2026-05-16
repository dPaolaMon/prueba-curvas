<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Bandeja de Entrada') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">{{ __('Mensajería') }}</li>
            <li class="breadcrumb-item">{{ __('Bandeja de Entrada') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        @include('mensajes.partials.flash')

        <div class="d-flex gap-2 justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2">
                <a href="{{ route('mensajes.index') }}" class="btn btn-primary btn-sm">Entrada</a>
                <a href="{{ route('mensajes.enviados') }}" class="btn btn-outline-secondary btn-sm">Enviados</a>
            </div>
            <a href="{{ route('mensajes.create') }}" class="btn btn-success btn-sm">Redactar</a>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 2rem;"></th>
                            <th>De</th>
                            <th>Asunto</th>
                            <th>Fecha</th>
                            <th style="width: 5rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($mensajes as $registro)
                        @php $leido = $registro->leido_en !== null; @endphp
                        <tr class="{{ $leido ? '' : 'fw-semibold' }}">
                            <td class="text-center">
                                @if(!$leido)
                                    <span class="badge rounded-pill text-bg-primary" title="No leído">&bull;</span>
                                @endif
                            </td>
                            <td>{{ $registro->mensaje->remitente->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('mensajes.show', $registro->mensaje_id) }}" class="link-body-emphasis link-underline-opacity-0 link-underline-opacity-75-hover">
                                    {{ $registro->mensaje->asunto ?: '(sin asunto)' }}
                                </a>
                            </td>
                            <td class="text-body-secondary small">{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger js-eliminar-entrada"
                                    data-url="{{ route('mensajes.destroy-entrada', $registro->mensaje_id) }}"
                                    title="Eliminar"
                                >
                                    <x-bote-eliminar />
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="alert alert-secondary mb-0 text-center" role="alert">
                                    Tu bandeja de entrada está vacía.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $mensajes->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-eliminar-entrada').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const url = this.dataset.url;

                    window.Swal.fire({
                        title: 'Eliminar mensaje',
                        text: '¿Deseas eliminar este mensaje de tu bandeja de entrada?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                        confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
                    }).then((result) => {
                        if (!result.isConfirmed) return;

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <input type="hidden" name="_method" value="DELETE">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    });
                });
            });
        });
    </script>
</x-app-layout>
