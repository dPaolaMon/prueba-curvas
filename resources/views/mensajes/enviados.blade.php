<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Mensajes Enviados') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">{{ __('Mensajería') }}</li>
            <li class="breadcrumb-item">{{ __('Enviados') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        @include('mensajes.partials.flash')

        <div class="d-flex gap-2 justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2">
                <a href="{{ route('mensajes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-inbox me-2"></i>Entrada
                </a>
                <a href="{{ route('mensajes.enviados') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-send me-2"></i>Enviados
                </a>
            </div>
            <a href="{{ route('mensajes.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-pencil-square me-2"></i>Redactar
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Para</th>
                            <th>Asunto</th>
                            <th>Fecha</th>
                            <th style="width: 5rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($mensajes as $mensaje)
                        <tr>
                            <td class="small text-body-secondary">
                                {{ $mensaje->destinatarios->pluck('destinatario.name')->filter()->join(', ') ?: 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('mensajes.show', $mensaje) }}" class="link-body-emphasis link-underline-opacity-0 link-underline-opacity-75-hover">
                                    {{ $mensaje->asunto ?: '(sin asunto)' }}
                                </a>
                            </td>
                            <td class="text-body-secondary small">{{ $mensaje->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger js-eliminar-enviado"
                                    data-url="{{ route('mensajes.destroy-enviados', $mensaje) }}"
                                    title="Eliminar"
                                >
                                    <x-bote-eliminar />
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-secondary mb-0 text-center" role="alert">
                                    No has enviado ningún mensaje aún.
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
            document.querySelectorAll('.js-eliminar-enviado').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const url = this.dataset.url;

                    window.Swal.fire({
                        title: 'Eliminar mensaje',
                        text: '¿Deseas eliminar este mensaje de tu bandeja de enviados?',
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
