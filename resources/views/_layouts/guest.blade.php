<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .rosa {
                --theme-color: #EF26A8;
                --bs-primary-rgb: 239, 38, 168;
            }

            .rosa .form-control:focus,
            .rosa .form-select:focus,
            .rosa textarea.form-control:focus,
            .rosa input.form-control:focus {
                border-color: #EF26A8 !important;
                box-shadow: 0 0 0 0.25rem rgba(239, 38, 168, 0.35) !important;
                outline: 0;
            }
        </style>

    </head>
    <body class="rosa">
        <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center py-4">
            <div class="mb-3">
                <a href="/" aria-label="{{ config('app.name', 'Laravel') }}">
                    <x-application-logo class="d-block" style="width: 80px; height: 80px; color:#EF26A8" />
                </a>
            </div>

            <div class="card shadow-sm" style="width: 100%; max-width: 480px;">
                <div class="card-body p-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
