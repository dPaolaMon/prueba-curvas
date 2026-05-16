<section>
    @php
        $selectedTheme = old('theme', session('theme', $user->theme ?? 'normal'));
        $themes = \App\Services\CommonDataService::getThemes();
    @endphp

    <header class="mb-4">
        <h3 class="h5 mb-2">{{ __('Seleccionar Tema') }}</h3>
        <p class="text-muted small">
            {{ __('Seleccione el tema que desea utilizar para la aplicación.') }}
        </p>
    </header>

    <form id="form-cambiar-tema" method="post" action="{{ route('profile.theme.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <div class="d-flex align-items-center me-3">
                <label for="cbo-cambiar-tema" class="visually-hidden">{{ __('Tema') }}</label>
                <select id="cbo-cambiar-tema" name="theme" class="form-select form-select-sm" aria-label="{{ __('Tema') }}">
                    @foreach($themes as $theme)
                        <option value="{{ $theme['value'] }}" @selected($selectedTheme === $theme['value'])>{{ __($theme['es']) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @error('theme')
            <div class="text-danger small mb-3">{{ $message }}</div>
        @enderror

        <div class="d-flex justify-content-end">
            <button id="btn-cambiar-tema" type="button" class="btn btn-primary">{{ __('Guardar Tema') }}</button>
        </div>
    </form>

    <script>
        (() => {
            const btn = document.getElementById('btn-cambiar-tema');
            const form = document.getElementById('form-cambiar-tema');
            const select = document.getElementById('cbo-cambiar-tema');

            if (!btn || !form || !select) return;

            btn.addEventListener('click', () => {
                if (!window.Swal) return;

                window.Swal.fire({
                    title: @json(__('¿Proceder con el cambio de tema?')),
                    theme:'auto',
                    text: @json(__('Una vez guardado, el tema se cargará en la aplicación.')),
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: @json(__('Guardar Tema')),
                    cancelButtonText: @json(__('Cancelar')),
                    confirmButtonColor: getComputedStyle(document.body).getPropertyValue('--theme-color').trim() || '#0d6efd',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        form.submit();
                        return true;
                    },
                    allowOutsideClick: () => !window.Swal.isLoading(),
                });
            });
        })();
    </script>

    @if (session('status') === 'theme-updated')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function () {
                    if (!window.Swal) return;

                    window.Swal.fire({
                        toast: true,
                        theme: 'auto',
                        position: 'top-end',
                        icon: 'success',
                        title: @js(__('Tema actualizado correctamente.')),
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true,
                    });
                }, 0);
            });
        </script>
    @endif
</section>
