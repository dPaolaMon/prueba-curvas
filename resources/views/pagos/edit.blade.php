<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Editar Pago: {{ $pago->folio_pago }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}" class="link-underline-opacity-0 link-body-emphasis">Pagos</a></li>
            <li class="breadcrumb-item">Editar</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="bg-white p-4 rounded shadow">
            @if($pago->estatus !== 'pendiente')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        if (!window.Swal) return;

                        window.Swal.fire({
                            toast: true,
                            theme: 'auto',
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Solo se pueden editar pagos pendientes.',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('pagos.update', $pago) }}">
                <input type="hidden" name="return_url" value="{{ old('return_url', $cancelUrl ?? '') }}">
                @include('pagos.partials.maint')
            </form>
        </div>
    </div>
</x-app-layout>
