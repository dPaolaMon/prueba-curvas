<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">Versiones del plan: {{ $plan->nombre }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">Administración</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">Planes</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planes.show', $plan) }}" class="link-underline-opacity-0 link-body-emphasis">{{ $plan->nombre }}</a></li>
            <li class="breadcrumb-item">Versiones</li>
        </ol>
    </x-slot>

    <div class="container py-4">
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
            <!--<a href="{{ $backUrl ?? route('planes.show', $plan) }}" class="btn btn-secondary">-->
            <a href="{{ route('planes.show', $plan) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Detalles
            </a>
            <a href="{{ route('planes.planes-versiones.create', ['plan' => $plan]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nueva Versión
            </a>
          </div>
        </div>

        @if($versiones->count() > 0)
            <div class="row g-3">
                @foreach($versiones as $version)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">{{ $version->nombre_comercial }}</h5>
                                    <small class="text-muted">
                                        Vigencia: {{ $version->vigencia_desde->format('d/m/Y') }}
                                        @if($version->vigencia_hasta)
                                            - {{ $version->vigencia_hasta->format('d/m/Y') }}
                                        @else
                                            - <strong>Sin fecha fin</strong>
                                        @endif
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $version->estado_publicacion === 'publicado' ? 'success' : 'warning' }} mb-2">
                                        {{ ucfirst($version->estado_publicacion) }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Inscripción:</strong> ${{ number_format($version->precio_inscripcion, 2) }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Mensualidad:</strong> ${{ number_format($version->precio_mensualidad, 2) }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Duración:</strong> {{ $version->meses_duracion }} meses
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Cobrables:</small> {{ $version->meses_cobrables }} | 
                                        <small class="text-muted">Gratis:</small> {{ $version->meses_gratis }}
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small class="text-muted">Comisión:</small> ${{ number_format($version->comision_monto, 2) }} | 
                                        <small class="text-muted">Retención:</small> ${{ number_format($version->retencion_monto, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de versión de plan">
                                    <a href="{{ route('planes-versiones.show', ['planVersion' => $version]) }}" class="btn btn-outline-primary" title="Ver detalles">
                                        <x-ojito-ver />
                                    </a>
                                @if($version->estado_publicacion === 'borrador')
                                    <a href="{{ route('planes-versiones.edit', $version) }}" class="btn btn-outline-secondary" title="Editar">
                                        <x-lapiz-editar />
                                    </a>
                                    <!--<button type="submit" form="publish-version-{{ $version->id }}" class="btn btn-outline-success" title="Publicar">
                                        <i class="bi bi-check-circle"></i>
                                    </button>-->
                                @endif
                                <!--if($version->estado_publicacion === 'publicado' && !$version->tienePagos())-->
                                @if(!$version->tienePagos())
                                    <button type="submit" form="delete-version-{{ $version->id }}" class="btn btn-outline-danger" title="Eliminar">
                                        <x-bote-eliminar />
                                    </button>
                                @endif
                                </div>
                                <!--if($version->estado_publicacion === 'borrador')
                                    <form id="publish-version-{{ $version->id }}" action="{{ route('planes-versiones.publish', $version) }}" method="POST" class="d-none">
                                        csrf
                                    </form>
                                endif-->
                                <!--if($version->estado_publicacion === 'publicado' && !$version->tienePagos())-->
                                @if(!$version->tienePagos())
                                    <form id="delete-version-{{ $version->id }}" action="{{ route('planes-versiones.destroy', $version) }}" method="POST" class="d-none js-delete-version-form" data-version="{{ $version->nombre_comercial }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-4">
                {{ $versiones->links() }}
            </div>
        @else
            <div class="alert alert-info text-center py-5">
                <p class="mb-0"><i class="bi bi-info-circle me-2"></i>No hay versiones registradas para este plan.</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nativeSubmit = HTMLFormElement.prototype.submit;

            document.querySelectorAll('form.js-delete-version-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    if (!window.Swal) return;

                    const nombreVersion = form.dataset.version || 'esta version';

                    window.Swal.fire({
                        title: 'Eliminar version',
                        text: `¿Seguro que deseas eliminar ${nombreVersion}? Esta accion no se puede deshacer.`,
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
