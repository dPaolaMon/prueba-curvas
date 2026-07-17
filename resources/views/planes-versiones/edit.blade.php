<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Editar Versión: {{ $planVersion->nombre_comercial }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">Planes</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planes.show', $planVersion->plan) }}" class="link-underline-opacity-0 link-body-emphasis">{{ $planVersion->plan->nombre }}</a></li>
            <li class="breadcrumb-item">Editar</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="bg-white p-4 rounded shadow">
            @if($planVersion->tienePagos())
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        if (!window.Swal) return;

                        window.Swal.fire({
                            toast: true,
                            theme: 'auto',
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Esta versión tiene pagos registrados. No se pueden editar los importes.',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('planes-versiones.update', $planVersion) }}">
                <input type="hidden" name="return_url" value="{{ old('return_url', $cancelUrl ?? '') }}">
                @include('planes-versiones.partials.maint')
            </form>
        </div>
    </div>
</x-app-layout>
