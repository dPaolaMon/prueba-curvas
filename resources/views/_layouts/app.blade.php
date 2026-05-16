<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Layout APP -->
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $allowedThemes = \App\Services\CommonDataService::getThemeValues();
        $allowedThemes = empty($allowedThemes) ? ['normal'] : $allowedThemes;
        $selectedTheme = session('theme', auth()->user()->theme ?? 'normal');
        $selectedTheme = in_array($selectedTheme, $allowedThemes, true) ? $selectedTheme : 'normal';
    @endphp
    <body class="theme-{{ $selectedTheme }}">
        <div class="min-vh-100 d-flex flex-column">
            @include('_layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-body border-bottom shadow-sm py-3">
                    <div class="container">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                {{ $header }}
                            </div>

                            @isset($breadcrumb)
                                <nav aria-label="breadcrumb" class="ms-md-auto">
                                    {{ $breadcrumb }}
                                </nav>
                            @endisset
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow-1 py-4">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
