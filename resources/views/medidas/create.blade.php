<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Registro Nueva Medida</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Socias') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('medidas.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Medidas') }}</a></li>
            <li class="breadcrumb-item">{{ __('Registro') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('medidas.store') }}">
                            @include('medidas.partials.maint')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
