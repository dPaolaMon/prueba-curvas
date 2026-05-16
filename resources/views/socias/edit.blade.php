<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Editar socia: {{ $socia->nombre }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Socias') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Listado') }}</a></li>
            <li class="breadcrumb-item">{{ __('Editar') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4" style="max-width: 900px;">
        <div class="bg-white p-4 rounded shadow">
            <form method="POST"
                  action="{{ route('socias.update', $socia) }}"
                  enctype="multipart/form-data">

                @include('socias.partials.maint')
            </form>
        </div>
    </div>
</x-app-layout>
