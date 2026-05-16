@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.Swal) return;

            window.Swal.fire({
                toast: true,
                theme: 'auto',
                position: 'top-end',
                icon: 'success',
                title: @js(session('success')),
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true,
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.Swal) return;

            window.Swal.fire({
                toast: true,
                theme: 'auto',
                position: 'top-end',
                icon: 'error',
                title: @js(session('error')),
                showConfirmButton: false,
                timer: 2600,
                timerProgressBar: true,
            });
        });
    </script>
@endif
