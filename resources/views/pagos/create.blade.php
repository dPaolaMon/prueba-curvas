<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Nuevo Pago</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}" class="link-underline-opacity-0 link-body-emphasis">Pagos</a></li>
            <li class="breadcrumb-item">Registrar</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="bg-white p-4 rounded shadow">
            <form method="POST" action="{{ route('pagos.store') }}">
                @include('pagos.partials.maint')
            </form>
        </div>
    </div>
</x-app-layout>
