<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body{
                background:#f5f6fa;
            }

            /* NAVBAR */
            .navbar-custom{
                background:#e61e9c !important;
            }

            .logo-box{
                background:white;
                padding:8px 16px;
                font-weight:bold;
                border-radius:6px;
            }

            /* HEADER DASHBOARD */
            .dashboard-header{
                margin-top:30px;
                margin-bottom:30px;
            }

            /* CARDS */
            .card-dashboard{
                border:none;
                border-left:6px solid #e61e9c;
                border-radius:10px;
                box-shadow:0 5px 15px rgba(0,0,0,.08);
                transition:.2s;
            }

            .card-dashboard:hover{
                transform:translateY(-3px);
                box-shadow:0 10px 20px rgba(0,0,0,.1);
            }

            /* FAB */
            .fab{
                position:fixed;
                right:30px;
                bottom:30px;

                width:65px;
                height:65px;

                border-radius:50%;

                background:#6f4cc3;

                color:white;

                display:flex;
                align-items:center;
                justify-content:center;

                font-size:28px;

                box-shadow:0 10px 25px rgba(0,0,0,.25);

                cursor:pointer;

                transition:.25s;
            }

            .fab:hover{
                transform:scale(1.08) rotate(5deg);
            }

            /* STATUS BOX */
            .status-box{
                text-align:right;
            }

            .status-active{
                color:#2ecc71;
                font-weight:600;
            }
        </style>
    </head>

    @php
        $kioskoHomeParams = array_filter([
            'token' => request()->query('token'),
            'num_socia' => request()->query('num_socia'),
        ], fn ($value) => filled($value));

        $kioskoHomeUrl = route('kiosko.inicio', $kioskoHomeParams);

        $kioskoExitUrl = request()->filled('token')
            ? route('kiosko.panel', ['token' => request()->query('token')])
            : route('kiosko.panel');
    @endphp

    <body>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top px-4">
            <a class="navbar-brand" href="{{ $kioskoHomeUrl }}" style="color:white;">
                <x-application-logo class="d-inline-block align-text-top" style="height: 36px; width: auto;" />
            </a>
            <button class="navbar-toggler ms-auto" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item px-2">
                        <a class="nav-link text-white" href="{{ $kioskoHomeUrl }}">Inicio</a>
                    </li>
                    <!--<li class="nav-item px-2">
                        <a class="nav-link text-white" href="#">Mi perfil</a>
                    </li>-->
                    <li class="nav-item px-2">
                        <a class="nav-link text-white" href="#">Mi membresía</a>
                    </li>
                    <!--<li class="nav-item px-2">
                        <a class="nav-link text-white" href="#">Clases / Citas</a>
                    </li>-->
                    <li class="nav-item px-2">
                        <a class="nav-link text-white" href="{{ $kioskoExitUrl }}">Salir</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            {{-- Contenido dinámico --}}
            {{ $slot }}
        </div>

        <!-- FAB -->
        <a href="{{ $kioskoExitUrl }}" class="fab text-decoration-none" aria-label="Salir al panel kiosko">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </body>
</html>
