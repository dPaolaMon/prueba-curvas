<nav class="navbar navbar-expand-lg bg-body border-bottom">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}" aria-label="{{ __('Dashboard') }}">
            <x-application-logo class="d-inline-block align-text-top" style="height: 36px; width: auto;" />
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="{{ __('Toggle navigation') }}"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Obtención de rol para dinamismo frontend manual --}}
        @php
            $administrador = auth()->check() && strtoupper((string) auth()->user()->role) === 'ADMINISTRADOR';
            $gerente = auth()->check() && strtoupper((string) auth()->user()->role) === 'GERENTE';
            $entrenadora = auth()->check() && strtoupper((string) auth()->user()->role) === 'ENTRENADORA';
            $socia = auth()->check() && strtoupper((string) auth()->user()->role) === 'SOCIA';
            $mensajesNoLeidos = auth()->check()
                ? auth()->user()->mensajesNoLeidos()->count()
                : 0;
        @endphp

        <div class="collapse navbar-collapse" id="mainNavbar">
            {{-- 
            ╔════════════════════════╗
            ║ Opciones de navegación     ║
            ╚════════════════════════╝--}}

            {{-- Dashboard --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            {{-- Gestión de Socias --}}
            @if( $administrador || $gerente )
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('Gestión de Socias') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a
                                class="dropdown-item"
                                href="#"
                                id="launch-kiosko-link"
                                rel="noopener"
                            >
                                {{ __('Iniciar kiosko asistencia') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('asistencia.index') }}">{{ __('Asistencia manual') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('socias.index') }}">{{ __('Listado') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('socias.create') }}">{{ __('Registro') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('membresias.index') }}">{{ __('Membresias') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('medidas.index') }}">{{ __('Medidas') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('Población especial') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('Cumpleaños') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('reportes.asistencia') }}">{{ __('Reporte de asistencia') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            {{-- Gestión Staff --}}
            @if( $administrador )
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('Gestión Staff') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('usuarios.index') }}">{{ __('Listado') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('usuarios.create') }}">{{ __('Registro') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            {{-- Rutinas --}}
            @if( $administrador || $gerente || $entrenadora )
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('ejercicios.*') || request()->routeIs('maquinas.*') || request()->routeIs('medidas.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('Rutinas') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('maquinas.index') }}">{{ __('Máquinas') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('ejercicios.index') }}">{{ __('Ejercicios') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('calendario.index') }}">{{ __('Calendario') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            {{-- Administrador de puntos --}}
            @if( $administrador )
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        {{ __('Admon de Puntos') }}
                    </a>
                </li>
            </ul>
            @endif

            {{-- Mi perfil --}}
            @if( $socia )
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('perfil-socia.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('Mi Perfil') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('perfil-socia.show') }}">{{ __('Perfil de Socia') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('progreso.para-socia') }}">{{ __('Progreso') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            {{-- Mi membresia --}}
            @if( $socia )
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('ejercicios.*') || request()->routeIs('maquinas.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('Mi Membresía') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">{{ __('Opción 1') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">{{ __('Opción 2') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">{{ __('Opción 3') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            {{-- Clases / citas --}}
            @if( $socia )
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('ejercicios.*') || request()->routeIs('maquinas.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('Clases / Citas') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">{{ __('Opción 1') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            {{-- Administración --}}
            @if( $administrador )
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('ejercicios.*') || request()->routeIs('maquinas.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('Administración') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('planes.index') }}">{{ __('Planes') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('pagos.index') }}">{{ __('Pagos') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            {{-- Mensajería (todos los roles) --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link position-relative {{ request()->routeIs('mensajes.*') ? 'active' : '' }}" href="{{ route('mensajes.index') }}">
                        {{ __('Mensajes') }}
                        @if($mensajesNoLeidos > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger" style="font-size: .65rem;">
                                {{ $mensajesNoLeidos > 99 ? '99+' : $mensajesNoLeidos }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>

            {{-- 
            ╔═══════════════════════════════╗
            ║ Enlaces de usuario registrado       ║
            ╚═══════════════════════════════╝--}}
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        {{ Auth::user()->name }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Perfil de usuario') }}</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ __('Cerrar Sesión') }}</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const launchKioskoLink = document.getElementById('launch-kiosko-link');

        if (!launchKioskoLink || !window.Swal) {
            return;
        }

        launchKioskoLink.addEventListener('click', async (event) => {
            event.preventDefault();

            const result = await window.Swal.fire({
                title: 'Iniciar kiosko de asistencia',
                text: '¿Desea abrir el registro de asistencia en otra pestaña? El módulo permanecerá activo aunque cierre su sesión.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Abrir Kiosko',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
            });

            if (result.isConfirmed) {
                iniciarKiosko();
            }
        });
    });

    function iniciarKiosko() {
        fetch('{{ route("kiosko.iniciar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => { 
            if (data.success) {
                window.open(data.url, '_blank');
            } else {
                window.Swal.fire({
                    toast: true,
                    theme: 'auto',
                    position: 'top-end',
                    icon: 'error',
                    title: data.error || 'No se pudo iniciar el kiosko.',
                    showConfirmButton: false,
                    showCloseButton: true,
                });
            }
        })
        .catch(error => {
            window.Swal.fire({
                toast: true,
                theme: 'auto',
                position: 'top-end',
                icon: 'error',
                title: error?.message || 'Error al iniciar el kiosko.',
                showConfirmButton: false,
                showCloseButton: true,
            });
        });
    }
</script>