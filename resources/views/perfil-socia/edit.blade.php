<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Editar Perfil de Socia</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('perfil-socia.show') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Mi Perfil') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('perfil-socia.show') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Perfil de Socia') }}</a></li>
            <li class="breadcrumb-item">{{ __('Editar') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11 col-xxl-10">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <form method="POST" action="{{ route('perfil-socia.update') }}" enctype="multipart/form-data">
                            @include('perfil-socia.partials.maint')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
