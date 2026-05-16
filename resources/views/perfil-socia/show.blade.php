<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Perfil de Socia</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">{{ __('Mi Perfil') }}</li>
            <li class="breadcrumb-item">{{ __('Perfil de Socia') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
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

        <div class="card shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4 align-items-start">
                    <div class="col-12 col-lg-3">
                        <div class="text-center">
                            @if($socia->foto)
                                <img
                                    src="{{ asset('storage/' . $socia->foto) }}"
                                    alt="Foto de {{ $socia->nombre }}"
                                    class="img-thumbnail w-100"
                                    style="max-width: 220px; object-fit: cover;"
                                >
                            @else
                                <div class="border rounded d-flex align-items-center justify-content-center text-body-secondary mx-auto" style="height: 220px; max-width: 220px;">
                                    Sin foto
                                </div>
                            @endif

                            <div class="mt-3">
                                <div class="fw-semibold fs-5">{{ $socia->nombre }} {{ $socia->apellidos }}</div>
                                <div class="text-body-secondary small">Socia #{{ $socia->num_socia }}</div>
                                <span class="badge text-bg-light border mt-2">{{ $socia->estatus ?? 'Sin estatus' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-9">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <h6 class="fw-bold mb-3">Datos personales</h6>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <div class="small text-body-secondary">Nombre</div>
                                            <div>{{ $socia->nombre ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="small text-body-secondary">Apellidos</div>
                                            <div>{{ $socia->apellidos ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="small text-body-secondary">Fecha de nacimiento</div>
                                            <div>{{ $socia->fecha_nacimiento?->format('Y-m-d') ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="small text-body-secondary">Estado civil</div>
                                            <div>{{ $socia->estado_civil ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="small text-body-secondary">Ocupación</div>
                                            <div>{{ $socia->ocupacion ?: 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <h6 class="fw-bold mb-3">Contacto y domicilio</h6>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <div class="small text-body-secondary">Celular</div>
                                            <div>{{ $socia->celular ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="small text-body-secondary">Email</div>
                                            <div>{{ $socia->email ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-body-secondary">Dirección</div>
                                            <div>{{ $socia->direccion ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="small text-body-secondary">Colonia</div>
                                            <div>{{ $socia->colonia ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="small text-body-secondary">Código postal</div>
                                            <div>{{ $socia->codigo_postal ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="small text-body-secondary">Municipio</div>
                                            <div>{{ $socia->municipio?->nombre ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="small text-body-secondary">Estado</div>
                                            <div>{{ $socia->estado?->nombre ?: 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <h6 class="fw-bold mb-3">Membresía y pagos</h6>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <div class="small text-body-secondary">Método de pago</div>
                                            <div>{{ $socia->metodo_pago ?: 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="small text-body-secondary">Fecha de alta</div>
                                            <div>{{ $socia->fecha_alta?->format('Y-m-d') ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="small text-body-secondary">Fecha de reingreso</div>
                                            <div>{{ $socia->fecha_reingreso?->format('Y-m-d') ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <h6 class="fw-bold mb-3">Salud y observaciones</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="small text-body-secondary">Contacto de emergencia</div>
                                            <div>{!! nl2br(e($socia->contacto_emergencia ?: 'N/A')) !!}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-body-secondary">Padecimiento crónico</div>
                                            <div>{!! nl2br(e($socia->padecimiento_cronico ?: 'N/A')) !!}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-body-secondary">Comentarios</div>
                                            <div>{!! nl2br(e($socia->comentarios ?: 'N/A')) !!}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="small text-body-secondary">Factor X</div>
                                            <div>{!! nl2br(e($socia->factorx ?: 'N/A')) !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-4 border-top mt-4">
                    <a href="{{ route('perfil-socia.edit') }}" class="btn btn-primary">Editar</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
