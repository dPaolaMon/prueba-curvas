<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Editar Membresía</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('socias.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Gestión de Socias') }}</a></li> 
            <li class="breadcrumb-item"><a href="{{ route('membresias.index') }}" class="link-underline-opacity-0 link-body-emphasis">Membresías</a></li>
            <li class="breadcrumb-item">Editar</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="bg-white p-4 rounded shadow">
            <form method="POST" action="{{ route('membresias.update', $membresia) }}">
                <input type="hidden" name="return_url" value="{{ old('return_url', $cancelUrl ?? '') }}">
                @include('membresias.partials.maint')
            </form>
        </div>
    </div>
</x-app-layout>
