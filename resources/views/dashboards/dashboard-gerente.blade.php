<x-app-layout>
    <style>
        .gerente-grid-title {
            background-color: var(--theme-color, #d4148e);
            color: #fff;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }

        .gerente-grid-table thead th {
            background-color: var(--theme-color-secondary, var(--bs-secondary-bg, #e9ecef));
            color: var(--bs-emphasis-color, #212529);
        }

        .gerente-btn-secondary {
            background-color: var(--theme-color-secondary, #6c757d);
            border-color: var(--theme-color-secondary, #6c757d);
            color: #fff;
        }

        .gerente-btn-secondary:hover,
        .gerente-btn-secondary:focus,
        .gerente-btn-secondary:active {
            background-color: var(--theme-color-secondary, #6c757d);
            border-color: var(--theme-color-secondary, #6c757d);
            color: #fff;
            opacity: 0.92;
        }
    </style>

    @if($cumpleaneras->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!window.Swal) {
                    return;
                }

                const cumpleaneras = @js($cumpleaneras);

                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Cumpleaños',
                    text: cumpleaneras.join(', '),
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: undefined,
                    timerProgressBar: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            });
        </script>
    @endif

    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Dashboard') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Inicio') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Dashboard') }}</a></li>
        </ol>
    </x-slot>

    <div class="container">
        <div class="alert alert-light border mb-3" role="alert">
            <span class="fw-semibold">Rol activo:</span>
            {{ ucfirst(strtolower($role)) }}
        </div>

        <div class="row g-3 mb-3">
            @foreach ($resumenCards as $card)
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h4 fw-semibold mb-2">{{ $card['title'] }}</h3>
                            <p class="display-6 mb-3">{{ $card['value'] }}</p>

                            @if (!empty($card['button']))
                                <div class="mt-auto text-end">
                                    <button type="button" class="btn btn-sm btn-secondary gerente-btn-secondary">{{ $card['button'] }}</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-3">
            @foreach ($secciones as $seccion)
                <div class="col-12 col-lg-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h4 class="h5 fw-semibold mb-3 gerente-grid-title">{{ $seccion['title'] }}</h4>

                            @if (!empty($seccion['columns']))
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 gerente-grid-table">
                                        <thead>
                                            <tr>
                                                @foreach ($seccion['columns'] as $columna)
                                                    <th>{{ $columna }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($seccion['rows'] as $fila)
                                                <tr>
                                                    @foreach ($fila as $valor)
                                                        <td>{{ $valor }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="border rounded bg-light-subtle flex-grow-1 d-flex align-items-center justify-content-center text-muted" style="min-height: 180px;">
                                    Placeholder de gráfico
                                </div>
                            @endif

                            @if (!empty($seccion['footer_button']))
                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-sm btn-secondary gerente-btn-secondary">{{ $seccion['footer_button'] }}</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
