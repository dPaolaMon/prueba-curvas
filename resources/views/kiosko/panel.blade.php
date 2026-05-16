<x-kiosko-layout>
    <div class="card shadow-lg w-100" style="max-width: 900px;">
        <div class="card-body p-4">
            <div class="row g-4">

                {{-- ============================================================
                     PANEL IZQUIERDO: teclado numérico
                ============================================================ --}}
                <div class="col-12 col-md-6 d-flex flex-column gap-3" id="panelTeclado">
                    {{-- Logo + reloj --}}
                    <div>
                        <a href="/" class="bg-theme" aria-label="{{ config('app.name') }}">
                            <x-application-logo style="height: 48px; width: auto;" />
                        </a>
                        <p id="hora" class="bg-theme display-3 fw-bold font-monospace mb-0 mt-2"></p>
                        <p id="fecha" class="text-muted small mb-0"></p>
                    </div>

                    {{-- Input --}}
                    <div>
                        <label class="form-label text-muted small">Núm. Socia</label>
                        <input id="inputSocia"
                               type="password"
                               inputmode="numeric"
                               readonly
                               class="form-control form-control-lg text-center fs-3 tracking-widest bg-light"
                               >
                    </div>

                    {{-- Teclado numérico --}}
                    <div class="d-grid gap-2" style="grid-template-columns: repeat(3, 1fr); display: grid;">
                        @foreach([1,2,3,4,5,6,7,8,9] as $n)
                            <button class="btn btn-outline-secondary btn-lg fs-4 btn-digito" data-digito="{{ $n }}">{{ $n }}</button>
                        @endforeach
                        <button class="btn btn-danger btn-lg fs-4" id="btnX">✕</button>
                        <button class="btn btn-outline-secondary btn-lg fs-4 btn-digito" data-digito="0">0</button>
                        <button class="btn btn-warning btn-lg fs-4" id="btnB">⌫</button>
                    </div>

                    {{-- Botón de asistencia --}}
                    <button id="btnAsistencia" class="btn btn-primary btn-lg w-100 mt-auto">
                        ✔ Asistencia
                    </button>
                </div>

                {{-- ============================================================
                     PANEL DERECHO: resultado socia
                ============================================================ --}}
                <div class="col-12 col-md-6 d-flex flex-column align-items-center justify-content-center text-center gap-3" id="panelSocia">

                    {{-- Estado inicial: esperando --}}
                    <div id="estadoEspera" class="text-muted">
                        <i class="bi bi-person-circle" style="font-size: 6rem; opacity: 0.2;"></i>
                        <p class="mt-3">Ingresa tu número de socia<br>y presiona <strong>Asistencia</strong></p>
                    </div>

                    {{-- Estado cargando --}}
                    <div id="estadoCargando" class="d-none">
                        <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                            <span class="visually-hidden">Buscando...</span>
                        </div>
                        <p class="mt-3 text-muted">Buscando socia...</p>
                    </div>

                    {{-- Estado: socia encontrada --}}
                    <div id="estadoSocia" class="d-none">
                        <img id="fotoSocia"
                             src=""
                             alt="Foto de socia"
                                class="rounded-circle img-thumbnail mb-2 d-none"
                             width="160" height="160"
                                style="object-fit: cover;">
                        <div id="avatarSocia" class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-2"
                             style="width:160px; height:160px; margin: 0 auto;">
                            <i class="bi bi-person-fill text-white" style="font-size: 5rem;"></i>
                        </div>
                        <h4 id="nombreSocia" class="fw-bold mb-0"></h4>
                        <p id="numSocia" class="text-muted">Socia #</p>
                        <span class="badge bg-success fs-6 mt-2">
                            <i class="bi bi-check-circle me-1"></i> Bienvenida, ingresando...
                        </span>
                        <div class="progress">
                            <div id="barra" class="progress-bar progress-bar-striped progress-bar-animated"
                                role="progressbar" style="width: 0%">
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        // --- Referencias DOM ---
        const inputSocia     = document.getElementById('inputSocia');
        const btnAsistencia  = document.getElementById('btnAsistencia');
        const btnX           = document.getElementById('btnX');
        const btnB           = document.getElementById('btnB');
        const panelTeclado   = document.getElementById('panelTeclado');
        const botonesDigito  = document.querySelectorAll('.btn-digito');

        const estadoEspera   = document.getElementById('estadoEspera');
        const estadoCargando = document.getElementById('estadoCargando');
        const estadoSocia    = document.getElementById('estadoSocia');
        const fotoSocia      = document.getElementById('fotoSocia');
        const avatarSocia    = document.getElementById('avatarSocia');
        const nombreSocia    = document.getElementById('nombreSocia');
        const numSocia       = document.getElementById('numSocia');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const kioskoToken = new URLSearchParams(window.location.search).get('token');

        // --- Teclado numérico ---
        botonesDigito.forEach(btn => {
            btn.addEventListener('click', () => {
                inputSocia.value += btn.dataset.digito;
            });
        });

        btnX.addEventListener('click', () => {
            inputSocia.value = '';
            mostrarEstado('espera');
        });

        btnB.addEventListener('click', () => {
            inputSocia.value = inputSocia.value.slice(0, -1);
        });

        escuchaTeclas(true);

        function escuchaTeclas(bandera) {
            if (bandera) {
                document.addEventListener('keydown', listenersBotones);
                btnAsistencia.addEventListener('click', buscarSocia);
            } else {
                document.removeEventListener('keydown', listenersBotones);
                btnAsistencia.removeEventListener('click', buscarSocia);
            }
        }

        function listenersBotones(e) {
            if (/^[0-9]$/.test(e.key))       inputSocia.value += e.key;
                else if (e.key === 'Backspace')   inputSocia.value = inputSocia.value.slice(0, -1);
                else if (e.key === 'Escape')      { inputSocia.value = ''; mostrarEstado('espera'); }
                else if (e.key === 'Enter')       buscarSocia();
        }

        function mostrarFotoSocia(urlFoto) {
            fotoSocia.onload = null;
            fotoSocia.onerror = null;

            if (!urlFoto) {
                fotoSocia.classList.add('d-none');
                fotoSocia.hidden = true;
                avatarSocia.classList.remove('d-none');
                avatarSocia.hidden = false;
                fotoSocia.removeAttribute('src');
                return;
            }

            avatarSocia.classList.add('d-none');
            avatarSocia.hidden = true;
            fotoSocia.classList.remove('d-none');
            fotoSocia.hidden = false;

            fotoSocia.onload = function () {
                avatarSocia.classList.add('d-none');
                avatarSocia.hidden = true;
                fotoSocia.classList.remove('d-none');
                fotoSocia.hidden = false;
            };

            fotoSocia.onerror = function () {
                fotoSocia.classList.add('d-none');
                fotoSocia.hidden = true;
                avatarSocia.classList.remove('d-none');
                avatarSocia.hidden = false;
            };

            fotoSocia.src = urlFoto;
        }

        function buscarSocia() {
            const num = inputSocia.value.trim();

            if (!kioskoToken) {
                window.Swal.fire({
                    icon: 'error',
                    title: 'Acceso no válido',
                    text: 'Token de kiosko requerido.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
                });
                return;
            }

            if (!num) {
                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Ingresa tu número de socia',
                    showConfirmButton: false,
                    timer: 2000,
                });
                return;
            }

            mostrarEstado('cargando');
            setTecladoHabilitado(false);

            const buscarUrl = new URL('{{ route("kiosko.buscar") }}', window.location.origin);
            buscarUrl.searchParams.set('token', kioskoToken);

            fetch(buscarUrl.toString(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ num_socia: num })
            })
            .then(response => response.json())
            .then(data => {
                //setTecladoHabilitado(true);

                if (data.error) {
                    mostrarEstado('espera');
                    window.Swal.fire({
                        icon: 'error',
                        title: 'Socia no encontrada',
                        text: data.error,
                        confirmButtonText: 'Intentar de nuevo',
                        confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
                    });
                    inputSocia.value = '';
                    setTecladoHabilitado(true);
                    return;
                }

                // Mostrar datos de la socia
                mostrarEstado('socia');
                nombreSocia.textContent = `${data.socia.nombre} ${data.socia.apellidos}`;
                numSocia.textContent = `Socia #${data.socia.num_socia}`;
                mostrarFotoSocia(data.socia.foto);

                //Mostrar progress bar animada durante 4 segundos
                let barra = document.getElementById("barra");
                let progreso = 0;

                let intervalo = setInterval(() => {
                    progreso += 2.5; // 100 / 40 pasos ≈ 4 segundos
                    barra.style.width = progreso + "%";
                    if (progreso >= 100) {
                        clearInterval(intervalo);
                    }
                }, 100);

                // Redirección después de 4 segundos a kiosko.inicio con token y número de socia
                setTimeout(() => {
                    const redirectUrl = new URL('{{ route("kiosko.inicio") }}', window.location.origin);

                    if (kioskoToken) {
                        redirectUrl.searchParams.set('token', kioskoToken);
                    }

                    redirectUrl.searchParams.set('num_socia', data.socia.num_socia);
                    window.location.href = redirectUrl.toString();
                }, 4000);
            })
            .catch(() => {
                setTecladoHabilitado(true);
                mostrarEstado('espera');
                window.Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Intenta nuevamente.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
                });
                inputSocia.value = '';
            });
        }

        function mostrarEstado(estado) {
            estadoEspera.classList.add('d-none');
            estadoCargando.classList.add('d-none');
            estadoSocia.classList.add('d-none');

            if (estado === 'espera')    estadoEspera.classList.remove('d-none');
            if (estado === 'cargando')  estadoCargando.classList.remove('d-none');
            if (estado === 'socia')     estadoSocia.classList.remove('d-none');
        }

        function setTecladoHabilitado(habilitado) {
            escuchaTeclas(habilitado);

            btnAsistencia.disabled  = !habilitado;
            btnX.disabled           = !habilitado;
            btnB.disabled           = !habilitado;
            botonesDigito.forEach(b => b.disabled = !habilitado);

            panelTeclado.disabled = !habilitado;
        }

        // --- Reloj ---
        function actualizarFechaHora() {
            const now = new Date();
            document.getElementById('hora').textContent =
                now.toLocaleTimeString('es-MX', { hour12: false });
            document.getElementById('fecha').textContent =
                now.toLocaleDateString('es-MX', {
                    weekday: 'long', day: 'numeric',
                    month: 'long', year: 'numeric'
                });
        }

        setInterval(actualizarFechaHora, 1000);
        actualizarFechaHora();
    </script>

</x-kiosko-layout>
