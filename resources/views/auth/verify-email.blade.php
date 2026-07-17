<x-guest-layout>
    <div class="alert alert-info" role="alert">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!window.Swal) return;

                window.Swal.fire({
                    toast: true,
                    theme: 'auto',
                    position: 'top-end',
                    icon: 'success',
                    title: @js(__('A new verification link has been sent to the email address you provided during registration.')),
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline-secondary btn-sm">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
