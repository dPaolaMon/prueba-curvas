<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ $mensaje->asunto ?: '(sin asunto)' }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('mensajes.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Mensajería') }}</a></li>
            <li class="breadcrumb-item">{{ __('Mensaje') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <div class="small text-body-secondary">De</div>
                            <div class="fw-semibold">{{ $mensaje->remitente->name ?? 'N/A' }}</div>
                        </div>
                        <div class="text-end">
                            <div class="small text-body-secondary">{{ $mensaje->created_at->format('d/m/Y H:i') }}</div>
                            @if($mensaje->asunto)
                                <div class="small text-body-secondary mt-1">Asunto: <span class="text-body">{{ $mensaje->asunto }}</span></div>
                            @endif
                        </div>
                    </div>

                    @if($mensaje->destinatarios->count() > 0)
                        <div class="card-header py-2 border-top-0">
                            <span class="small text-body-secondary">Para: </span>
                            <span class="small">
                                {{ $mensaje->destinatarios->pluck('destinatario.name')->filter()->join(', ') }}
                            </span>
                        </div>
                    @endif

                    <div class="card-body p-4">
                        <div style="white-space: pre-wrap; word-break: break-word;">{{ $mensaje->cuerpo }}</div>
                    </div>

                    <div class="card-footer d-flex gap-2 justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Regresar</a>

                        <div class="d-flex gap-2">
                            @if($esDestinatario)
                                <button
                                    type="button"
                                    class="btn btn-outline-danger btn-sm js-eliminar-entrada"
                                    data-url="{{ route('mensajes.destroy-entrada', $mensaje) }}"
                                >
                                    Eliminar de entrada
                                </button>
                            @endif

                            @if($esRemitente)
                                <button
                                    type="button"
                                    class="btn btn-outline-danger btn-sm js-eliminar-enviado"
                                    data-url="{{ route('mensajes.destroy-enviados', $mensaje) }}"
                                >
                                    Eliminar de enviados
                                </button>
                            @endif

                            <a href="{{ route('mensajes.create') }}" class="btn btn-primary btn-sm">Redactar nuevo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function confirmarEliminar(selector, texto) {
                document.querySelectorAll(selector).forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const url = this.dataset.url;

                        window.Swal.fire({
                            title: 'Eliminar mensaje',
                            text: texto,
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
            }

            confirmarEliminar('.js-eliminar-entrada', '¿Deseas eliminar este mensaje de tu bandeja de entrada?');
            confirmarEliminar('.js-eliminar-enviado', '¿Deseas eliminar este mensaje de tu bandeja de enviados?');
        });
    </script>
</x-app-layout>
