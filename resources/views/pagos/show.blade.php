<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ $pago->folio_pago  }}</h2>    
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}" class="link-underline-opacity-0 link-body-emphasis">Pagos</a></li>
            <li class="breadcrumb-item">{{ $pago->folio_pago }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">

        <!-- Botones de acción generales -->
        <div class="row g-2 align-items-end mb-3 justify-content-end">
          <div class="col-12 col-md-auto d-flex gap-2">
            <a href="{{ route('pagos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Pagos
            </a>
          </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8">
                {{-- Información General --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Información General</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Folio:</div>
                        <div class="col-sm-9"><strong>{{ $pago->folio_pago }}</strong></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Socia:</div>
                        <div class="col-sm-9">{{ $pago->socia->nombre }} {{ $pago->socia->apellidos }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Plan:</div>
                        <div class="col-sm-9">{{ $pago->planVersion->plan->nombre }} - {{ $pago->planVersion->nombre_comercial }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Tipo:</div>
                        <div class="col-sm-9"><span class="badge bg-secondary">{{ ucfirst($pago->tipo_pago) }}</span></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 text-muted">Estatus:</div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ match($pago->estatus) { 'aplicado' => 'success', 'pendiente' => 'warning', 'anulado' => 'danger', 'reembolsado' => 'secondary', default => 'info' } }}">
                                {{ ucfirst($pago->estatus) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Detalles de Pago --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Detalles de Pago</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Método:</div>
                        <div class="col-sm-9">{{ $pago->metodo_pago }}</div>
                    </div>

                    @if($pago->referencia_externa)
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">Referencia:</div>
                            <div class="col-sm-9">{{ $pago->referencia_externa }}</div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Fecha Pago:</div>
                        <div class="col-sm-9">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</div>
                    </div>

                    @if($pago->fecha_programada)
                        <div class="row">
                            <div class="col-sm-3 text-muted">Programada:</div>
                            <div class="col-sm-9">{{ $pago->fecha_programada->format('d/m/Y') }}</div>
                        </div>
                    @endif
                </div>

                {{-- Importes --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h5 class="mb-4">Importes</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">Monto Lista:</td>
                                <td class="text-end">${{ number_format($pago->monto_lista, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Descuento:</td>
                                <td class="text-end text-danger">-${{ number_format($pago->monto_descuento, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Recargo:</td>
                                <td class="text-end text-success">+${{ number_format($pago->monto_recargo, 2) }}</td>
                            </tr>
                            <tr class="table-active fw-bold">
                                <td>Monto Final:</td>
                                <td class="text-end">${{ number_format($pago->monto_final, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Comisión y Retención --}}
                <div class="bg-white p-4 rounded shadow">
                    <h5 class="mb-4">Comisión y Retención</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">Comisión:</td>
                                <td class="text-end">${{ number_format($pago->comision_monto, 2) }}</td>
                            </tr>
                            @if($pago->comision_pagable_en)
                                <tr>
                                    <td class="text-muted">Pagable en:</td>
                                    <td class="text-end">{{ $pago->comision_pagable_en->format('d/m/Y') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Retención:</td>
                                <td class="text-end">${{ number_format($pago->retencion_monto, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">¿Aplica?:</td>
                                <td class="text-end">
                                    @if($pago->retencion_aplica)
                                        <span class="badge bg-success">Sí</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            @if($pago->retencion_liberable_en)
                                <tr>
                                    <td class="text-muted">Liberable en:</td>
                                    <td class="text-end">{{ $pago->retencion_liberable_en->format('d/m/Y') }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- Panel Lateral --}}
            <div class="col-12 col-lg-4">
                {{-- Acciones --}}
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h6 class="mb-3">Acciones</h6>

                    @if($pago->estatus === 'pendiente')
                        <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-warning w-100 btn-sm mb-2">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger w-100 btn-sm js-cancelar-pago"
                            data-url="{{ route('pagos.cancel', $pago) }}"
                        >
                            <i class="bi bi-x-circle me-2"></i>Anular
                        </button>
                    @elseif($pago->estatus === 'aplicado')
                        <button
                            type="button"
                            class="btn btn-warning w-100 btn-sm js-reembolsar-pago"
                            data-url="{{ route('pagos.reembolso', $pago) }}"
                            data-monto="{{ number_format($pago->monto_final, 2) }}"
                        >
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reembolsar
                        </button>
                    @endif

                    <a href="{{ route('membresias.show', $pago->membresia) }}" class="btn btn-outline-primary w-100 btn-sm mt-2">
                        <i class="bi bi-eye me-2"></i>Ver Membresía
                    </a>
                </div>

                {{-- Historial --}}
                <div class="bg-light p-4 rounded mb-4">
                    <h6 class="mb-3">Historial</h6>
                    <small class="d-block text-muted mb-2">Registrado: {{ $pago->created_at->format('d/m/Y H:i') }}</small>
                    <small class="d-block text-muted">Por: {{ $pago->registradoPor?->name ?? 'N/A' }}</small>

                    @if($pago->estaAnulado())
                        <hr>
                        <small class="d-block text-muted mb-2">Anulado: {{ $pago->anulado_at->format('d/m/Y H:i') }}</small>
                        <small class="d-block text-muted">Por: {{ $pago->anuladoPor?->name ?? 'N/A' }}</small>
                        <small class="d-block text-danger">Motivo: {{ $pago->motivo_anulacion }}</small>
                    @endif
                </div>

                {{-- Snapshot JSON --}}
                @if($pago->snapshot_json)
                    <div class="bg-white p-4 rounded">
                        <h6 class="mb-3">Snapshot</h6>
                        <div style="font-size: 0.75rem; background: #f5f5f5; padding: 10px; border-radius: 4px; max-height: 200px; overflow-y: auto;">
                            <pre class="mb-0">{{ json_encode($pago->snapshot_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function enviarConMotivo(url, motivo) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const motivoInput = document.createElement('input');
                motivoInput.type = 'hidden';
                motivoInput.name = 'motivo_anulacion';
                motivoInput.value = motivo;

                form.appendChild(csrf);
                form.appendChild(motivoInput);
                document.body.appendChild(form);
                form.submit();
            }

            const botonCancelar = document.querySelector('.js-cancelar-pago');
            if (botonCancelar) {
                botonCancelar.addEventListener('click', async function () {
                    if (!window.Swal) {
                        return;
                    }

                    const result = await window.Swal.fire({
                        title: 'Anular pago',
                        input: 'textarea',
                        inputLabel: 'Motivo',
                        inputPlaceholder: 'Escribe el motivo de anulacion',
                        inputAttributes: {
                            'aria-label': 'Motivo de anulacion',
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Anular',
                        confirmButtonColor: '#dc3545',
                        cancelButtonText: 'Cancelar',
                        preConfirm: (value) => {
                            if (!value || !value.trim()) {
                                window.Swal.showValidationMessage('El motivo es obligatorio.');
                                return false;
                            }

                            return value.trim();
                        },
                    });

                    if (!result.isConfirmed) {
                        return;
                    }

                    enviarConMotivo(this.dataset.url, result.value);
                });
            }

            const botonReembolso = document.querySelector('.js-reembolsar-pago');
            if (botonReembolso) {
                botonReembolso.addEventListener('click', async function () {
                    if (!window.Swal) {
                        return;
                    }

                    const result = await window.Swal.fire({
                        title: 'Reembolsar pago',
                        html: `Monto a reembolsar: <strong>$${this.dataset.monto}</strong>`,
                        input: 'textarea',
                        inputLabel: 'Motivo',
                        inputPlaceholder: 'Escribe el motivo de reembolso',
                        inputAttributes: {
                            'aria-label': 'Motivo de reembolso',
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar reembolso',
                        confirmButtonColor: '#ffc107',
                        cancelButtonText: 'Cancelar',
                        preConfirm: (value) => {
                            if (!value || !value.trim()) {
                                window.Swal.showValidationMessage('El motivo es obligatorio.');
                                return false;
                            }

                            return value.trim();
                        },
                    });

                    if (!result.isConfirmed) {
                        return;
                    }

                    enviarConMotivo(this.dataset.url, result.value);
                });
            }
        });
    </script>
</x-app-layout>
