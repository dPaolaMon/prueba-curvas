<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Registro Nueva Máquina</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('maquinas.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Máquinas') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('maquinas.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Listado') }}</a></li>
            <li class="breadcrumb-item">{{ __('Registro') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('maquinas.store') }}">
                            @include('maquinas.partials.maint')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
