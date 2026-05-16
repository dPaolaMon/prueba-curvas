<section>
    <header class="mb-4">
        <h3 class="h5 mb-2">{{ __('Actualizar Contraseña') }}</h3>
        <p class="text-muted small">{{ __('Asegúrese de que su cuenta esté utilizando una contraseña larga y aleatoria para mantenerse seguro.') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Contraseña Actual') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('Nueva Contraseña') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
            @error('password', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirmar Contraseña') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">{{ __('Guardar Contraseña') }}</button>
        </div>
    </form>

    @if (session('status') === 'password-updated')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function () {
                    if (!window.Swal) return;

                    window.Swal.fire({
                        toast: true,
                        theme: 'auto',
                        position: 'top-end',
                        icon: 'success',
                        title: @js(__('Contraseña actualizada correctamente.')),
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true,
                    });
                }, 0);
            });
        </script>
    @endif
</section>
