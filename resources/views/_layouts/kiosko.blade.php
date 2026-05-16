<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de Asistencia - {{ config('app.name') }}</title>
    <!-- LAYOUT KIOSKO -->
        
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="theme-pink bg-light min-vh-100 d-flex align-items-center justify-content-center p-3">
        
        <!-- Page Content -->
        {{ $slot }}
       
    </body>
</html>
