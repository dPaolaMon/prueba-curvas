<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Registro de Pagos') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Administración') }}</a></li>            
	        <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Pagos') }}</a></li>
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
            <a href="{{ route('pagos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Pago
            </a>
          </div>
        </div>

        <div class="bg-white p-4 rounded shadow">
            {{-- Búsqueda --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('pagos.index') }}" class="row g-3">
                    <div class="col-12 col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por folio o socia..."
                               value="{{ $search }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <select name="estatus" class="form-select">
                            <option value="todos">Todos los estatus</option>
                            <option value="aplicado" @selected($estatus === 'aplicado')>Aplicado</option>
                            <option value="pendiente" @selected($estatus === 'pendiente')>Pendiente</option>
                            <option value="anulado" @selected($estatus === 'anulado')>Anulado</option>
                            <option value="reembolsado" @selected($estatus === 'reembolsado')>Reembolsado</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-search me-2"></i>Buscar
                        </button>
                        <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                        </a>
                    </div>
                </form>
            </div>

            @if($pagos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Folio</th>
                                <th>Socia</th>
                                <th>Tipo</th>
                                <th class="text-end">Monto</th>
                                <th>Fecha Pago</th>
                                <th>Estatus</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pagos as $pago)
                                <tr>
                                    <td><strong>{{ $pago->folio_pago }}</strong></td>
                                    <td>{{ $pago->socia->nombre }} {{ $pago->socia->apellidos }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($pago->tipo_pago) }}</span></td>
                                    <td class="text-end font-monospace"><strong>${{ number_format($pago->monto_final, 2) }}</strong></td>
                                    <td>{{ $pago->fecha_pago->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ match($pago->estatus) { 'aplicado' => 'success', 'pendiente' => 'warning', 'anulado' => 'danger', 'reembolsado' => 'secondary', default => 'info' } }}">
                                            {{ ucfirst($pago->estatus) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de pago">
                                            <a href="{{ route('pagos.show', $pago) }}" class="btn btn-outline-primary" title="Ver detalles">
                                                <x-ojito-ver />
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $pagos->links() }}
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <p class="mb-0"><i class="bi bi-info-circle me-2"></i>No hay pagos registrados.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
