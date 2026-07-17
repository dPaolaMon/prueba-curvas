<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ ucfirst(strtolower($membresia->planVersion->nombre_comercial)) }} - {{ ucfirst(strtolower($membresia->socia->nombre)) }} {{ ucfirst(strtolower($membresia->socia->apellidos)) }}</h2>  
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">Gestión de Socias</a></li>
            <li class="breadcrumb-item"><a href="{{ route('membresias.index') }}" class="link-underline-opacity-0 link-body-emphasis">Membresías</a></li>
            <li class="breadcrumb-item">Detalles</li>
        </ol>
    </x-slot>

    <div class="container py-4">

        <!-- Botones de acción generales -->
        <div class="row g-2 align-items-end mb-3 justify-content-end">
          <div class="col-12 col-md-auto d-flex gap-2">
            <a href="{{ route('membresias.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Membresías
            </a>
            <a href="{{ route('membresias.edit', $membresia) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>Editar
            </a>
          </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8">
                {{-- Información General --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Información General</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Número de Socia:</div>
                        <div class="col-sm-9">#{{ $membresia->socia->num_socia }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Plan:</div>
                        <div class="col-sm-9">{{ $membresia->planVersion->plan->nombre }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Versión:</div>
                        <div class="col-sm-9">{{ $membresia->planVersion->nombre_comercial }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Estatus:</div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ match($membresia->estatus) { 'activa' => 'success', 'pausada' => 'warning', 'cancelada' => 'danger', 'vencida' => 'secondary', default => 'info' } }}">
                                {{ ucfirst($membresia->estatus) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Fechas --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Períodos</h5>
                    
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <small class="text-muted">Inicio</small>
                            <div class="h5 mb-0">{{ $membresia->fecha_inicio->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-4 text-center border-end">
                            <small class="text-muted">Fin Programada</small>
                            <div class="h5 mb-0">{{ $membresia->fecha_fin_programada->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-4 text-center">
                            <small class="text-muted">Días Restantes</small>
                            <div class="h5 mb-0">{{ $membresia->obtenDiasRestantes() }}</div>
                        </div>
                    </div>

                    @if($membresia->fecha_cancelacion)
                        <hr>
                        <div class="alert alert-danger mb-0">
                            <small><strong>Cancelada:</strong> {{ $membresia->fecha_cancelacion->format('d/m/Y') }}</small>
                        </div>
                    @endif
                </div>

                {{-- Pago --}}
                <div class="bg-white p-4 rounded shadow">
                    <h5 class="mb-4">Información de Pago</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Método:</div>
                        <div class="col-sm-9">{{ $membresia->metodo_pago }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Ciclo:</div>
                        <div class="col-sm-9">{{ ucfirst($membresia->ciclo_facturacion) }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Día de Cobro:</div>
                        <div class="col-sm-9">{{ $membresia->dia_cobro ? 'Día ' . $membresia->dia_cobro : 'Sin especificar' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 text-muted">Período de Gracia:</div>
                        <div class="col-sm-9">{{ $membresia->periodo_gracia_dias }} días</div>
                    </div>
                </div>
            </div>

            {{-- Panel Lateral --}}
            <div class="col-12 col-lg-4">
                {{-- Acciones --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h6 class="mb-3">Acciones</h6>

                    <a href="{{ route('pagos.create') }}?membresia_id={{ $membresia->id }}" class="btn btn-primary w-100 btn-sm mt-2">
                        <i class="bi bi-plus-circle me-2"></i>Registrar Pago
                    </a>
                </div>

                {{-- Pagos Registrados --}}
                <div class="bg-light p-4 rounded mb-4">
                    <h6 class="mb-3">Pagos Registrados</h6>
                    <div class="h4 mb-0">{{ $membresia->pagos()->count() }}</div>
                    <small class="text-muted">Total: ${{ number_format($membresia->pagos()->sum('monto_final'), 2) }}</small>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
