<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Dashboard') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Inicio') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Dashboard') }}</a></li>
        </ol>
    </x-slot>

    <div class="container">
        <div class="alert alert-light border mb-3" role="alert">
            <span class="fw-semibold">Rol activo:</span>
            {{ ucfirst(strtolower($role)) }}
        </div>

        <div class="row g-3">
            @foreach ($widgets as $widget)
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <p class="text-muted small mb-1">{{ $widget['title'] }}</p>
                            <p class="fs-2 fw-semibold mb-1">{{ $widget['value'] }}</p>

                            @if (!empty($widget['badge']))
                                <span class="badge {{ $widget['badge']['class'] }}">{{ $widget['badge']['text'] }}</span>
                            @endif

                            @if (!empty($widget['note']))
                                <p class="text-muted small mb-0">{{ $widget['note'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
