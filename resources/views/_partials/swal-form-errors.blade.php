@php
    $title = $title ?? 'Errores en el formulario';
@endphp

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.Swal) return;

            window.Swal.fire({
                toast: true,
                theme: 'auto',
                position: 'top-end',
                icon: 'error',
                title: @js($title),
                html: @json('<ul class="text-start mb-0 ps-3">' . collect($errors->all())->map(fn($error) => '<li>' . e($error) . '</li>')->implode('') . '</ul>'),
                showConfirmButton: false,
                showCloseButton: true,
            });
        });
    </script>
@endif