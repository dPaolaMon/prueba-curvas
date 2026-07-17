<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Detalles de Socia') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Socias') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Listado') }}</a></li>
            <li class="breadcrumb-item">{{ __('Detalles de Socia') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">

        <!-- Botones de acción generales -->
        <div class="row g-2 align-items-end mb-3 justify-content-end">
          <div class="col-12 col-md-auto d-flex gap-2">
            <a href="{{ route('socias.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Listado de Socias
            </a>
            <a href="{{ route('socias.edit', $socia) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>Editar
            </a>
            <a href="{{ route('membresias.create', ['socia_id' => $socia->id]) }}" class="btn btn-outline-warning">
                <i class="bi bi-plus-circle me-2"></i>Asociar Membresía
            </a>
            <a href="{{ route('medidas.create', ['socia_id' => $socia->id]) }}" class="btn btn-outline-success">
                <i class="bi bi-rulers me-2"></i>Registrar medida
            </a>
          </div>
        </div>

        {{-- SECCIÓN: Información General --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h5 mb-0">Datos Personales</h3>
            </div>

            <div class="card-body">
                <div class="row g-3 mb-4 align-items-start">
                    {{-- Foto --}}
                    <div class="col-12 col-md-auto">
                        @if($socia->foto)
                            <img src="{{ asset('storage/' . $socia->foto) }}"
                                 alt="Foto de {{ $socia->nombre }}"
                                 class="img-thumbnail"
                                 width="128"
                                 height="128">
                        @else
                            <div class="border rounded d-flex align-items-center justify-content-center text-body-secondary p-5">
                                Sin foto
                            </div>
                        @endif
                    </div>

                    {{-- Información básica --}}
                    <div class="col">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="border rounded p-3 bg-light-subtle">
                                    <div class="small text-body-secondary">No. Socia</div>
                                    <div class="fs-5 fw-bold">{{ $socia->num_socia }}</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded p-3 bg-light-subtle">
                                    <div class="small text-body-secondary">Estatus</div>
                                    <div>
                                        <span class="badge {{ $socia->estatus === 'Activa' ? 'text-bg-success' : 'text-bg-danger' }}">
                                            {{ $socia->estatus }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded p-3 bg-light-subtle">
                                    <div class="small text-body-secondary">Fecha de Alta</div>
                                    <div class="fw-semibold">{{ $socia->fecha_alta?->format('d/m/Y') ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded p-3 bg-light-subtle">
                                    <div class="small text-body-secondary">Fecha de Reingreso</div>
                                    <div class="fw-semibold">{{ $socia->fecha_reingreso?->format('d/m/Y') ?? 'N/A' }}</div>
                                </div>
                            </div>

                            @if($socia->fecha_baja)
                            <div class="col-12 col-md-6">
                                <div class="border rounded p-3 bg-light-subtle">
                                    <div class="small text-body-secondary">Fecha de Baja</div>
                                    <div class="fw-semibold text-danger">{{ $socia->fecha_baja->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="small text-body-secondary">Nombre Completo</div>
                        <div>{{ $socia->nombre }} {{ $socia->apellidos }}</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="small text-body-secondary">Correo Electrónico</div>
                        <div>{{ $socia->email ?? 'N/A' }}</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="small text-body-secondary">Celular</div>
                        <div>{{ $socia->celular }}</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="small text-body-secondary">Fecha de Nacimiento</div>
                        <div>{{ $socia->fecha_nacimiento?->format('d/m/Y') ?? 'N/A' }}</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="small text-body-secondary">Edad</div>
                        <div>{{ $socia->fecha_nacimiento ? (int) $socia->fecha_nacimiento->age . ' años' : 'N/A' }}</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="small text-body-secondary">Estado Civil</div>
                        <div>{{ $socia->estado_civil ?? 'N/A' }}</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="small text-body-secondary">Ocupación</div>
                        <div>{{ $socia->ocupacion ?? 'N/A' }}</div>
                    </div>

                    <!-- <div class="col-12 col-md-6 col-lg-4">
                        <div class="small text-body-secondary">Método de Pago</div>
                        <div>{{ $socia->metodo_pago }}</div>
                    </div> -->
                </div>
            </div>
        </div>

        {{-- SECCIÓN: Ubicación --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h5 mb-0">Ubicación</h3>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="small text-body-secondary">Estado</div>
                        <div>{{ $socia->estado->nombre ?? 'N/A' }}</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="small text-body-secondary">Municipio</div>
                        <div>{{ $socia->municipio->nombre ?? 'N/A' }}</div>
                    </div>

                    <div class="col-12">
                        <div class="small text-body-secondary">Dirección</div>
                        <div>{{ $socia->direccion ?? 'N/A' }}</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="small text-body-secondary">Colonia</div>
                        <div>{{ $socia->colonia ?? 'N/A' }}</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="small text-body-secondary">Código Postal</div>
                        <div>{{ $socia->codigo_postal ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN: Información Médica --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h5 mb-0">Información Médica</h3>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="small text-body-secondary">Contacto de Emergencia</div>
                        <div>{!! nl2br(e($socia->contacto_emergencia ?? 'N/A')) !!}</div>
                    </div>

                    <div class="col-12">
                        <div class="small text-body-secondary">Lesión / Padecimiento Crónico</div>
                        <div>{!! nl2br(e($socia->padecimiento_cronico ?? 'N/A')) !!}</div>
                    </div>

                    <div class="col-12">
                        <div class="small text-body-secondary">Factor X</div>
                        <div>{!! nl2br(e($socia->factorx ?? 'N/A')) !!}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN: Comentarios --}}
        @if($socia->comentarios)
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h5 mb-0">Comentarios</h3>
            </div>

            <div class="card-body">
                <div>{!! nl2br(e($socia->comentarios)) !!}</div>
            </div>
        </div>
        @endif

        {{-- Usuario y Fechas de Registro --}}
        <div class="card">
            <div class="card-body">
                <div class="row g-3 small text-body-secondary">
                    <div class="col-12 col-md-4">
                        <span class="fw-semibold">Registrada por:</span>
                        <span>{{ $socia->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-12 col-md-4">
                        <span class="fw-semibold">Fecha de registro:</span>
                        <span>{{ $socia->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                    </div>
                    <div class="col-12 col-md-4">
                        <span class="fw-semibold">Última actualización:</span>
                        <span>{{ $socia->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
