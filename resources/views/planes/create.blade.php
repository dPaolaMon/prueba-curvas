<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Nuevo Plan</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">Administración</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planes.index') }}" class="link-underline-opacity-0 link-body-emphasis">Planes</a></li>
            <li class="breadcrumb-item">Crear</li>
        </ol>
    </x-slot>

    <div class="container py-4" style="max-width: 800px;">
        <div class="bg-white p-4 rounded shadow">
            <form method="POST"
                  action="{{ route('planes.store') }}">

                @include('planes.partials.maint')
            </form>
        </div>
    </div>
</x-app-layout>
