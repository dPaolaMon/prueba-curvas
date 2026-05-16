<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Crear Nuevo Usuario</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}" class="link-underline-opacity-0 link-body-emphasis">Gestión Staff</a></li>
            <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}" class="link-underline-opacity-0 link-body-emphasis">Listado</a></li>
            <li class="breadcrumb-item">Registro</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('usuarios.store') }}" method="POST">
                            @include('staff.partials.maint')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
