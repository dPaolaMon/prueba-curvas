<section>
    <header class="mb-4">
        <h3 class="h5 mb-2">{{ __('Eliminar Cuenta') }}</h3>
        <p class="text-muted small">
            {{ __('Una vez eliminada su cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.') }}
        </p>
    </header>

    {{-- Mostramos los errores en el bag, si es que hay --}}
    @error('password', 'userDeletion')
        <div class="text-danger small mt-1 pb-3">
            {{ $message }}
        </div>
    @enderror

    <button id="confirm-user-deletion" type="button" class="btn btn-danger"><i class="bi bi-trash me-2"></i>{{ __('Eliminar Cuenta') }}</button>

    <form id="delete-user-form" method="post" action="{{ route('profile.destroy') }}" class="d-none">
        @csrf
        @method('delete')
        <input id="delete-user-password" type="hidden" name="password" value="">
    </form>

    <script>
        (() => {
            const btn = document.getElementById('confirm-user-deletion');
            const form = document.getElementById('delete-user-form');
            const passwordInput = document.getElementById('delete-user-password');

            if (!btn || !form || !passwordInput) return;

            btn.addEventListener('click', () => {
                if (!window.Swal) return;

                window.Swal.fire({
                    title: @json(__('¿Proceder en la eliminación de su cuenta?')),
                    theme:'auto',
                    text: @json(__('Una vez eliminada su cuenta, todos sus recursos y datos se eliminarán permanentemente. Ingrese su contraseña para confirmar que desea eliminar su cuenta permanentemente.')),
                    icon: 'warning',
                    input: 'password',
                    inputLabel: @json(__('Contraseña')),
                    inputPlaceholder: @json(__('Contraseña')),
                    showCancelButton: true,
                    confirmButtonText: @json(__('Eliminar Cuenta')),
                    cancelButtonText: @json(__('Cancelar')),
                    confirmButtonColor: '#dc2626',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: (password) => {
                        if (!password) {
                            window.Swal.showValidationMessage(@json(__('Por favor ingrese su contraseña.')));
                            return false;
                        }

                        passwordInput.value = password;
                        form.submit();

                        return true;
                    },
                    allowOutsideClick: () => !window.Swal.isLoading(),
                });
            });
        })();
    </script>
</section>
