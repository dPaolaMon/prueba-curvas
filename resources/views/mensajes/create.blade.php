<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 mb-0">{{ __('Redactar Mensaje') }}</h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('mensajes.index') }}" class="link-underline-opacity-0 link-body-emphasis">{{ __('Mensajería') }}</a></li>
            <li class="breadcrumb-item">{{ __('Redactar') }}</li>
        </ol>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading">Errores en el formulario:</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('mensajes.store') }}">
                            @csrf

                            {{-- Destinatarios --}}
                            <div class="mb-3">
                                <label for="destinatarios" class="form-label fw-semibold">
                                    Para <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="destinatarios"
                                    name="destinatarios[]"
                                    class="form-select"
                                    multiple
                                    required
                                    size="5"
                                >
                                    @foreach($destinatariosDisponibles as $usuario)
                                        <option
                                            value="{{ $usuario->id }}"
                                            @if(is_array(old('destinatarios')) && in_array($usuario->id, old('destinatarios'))) selected @endif
                                        >
                                            {{ $usuario->name }} ({{ ucfirst(strtolower($usuario->role)) }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Mantén Ctrl / ⌘ para seleccionar varios destinatarios.</div>
                                @error('destinatarios')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @error('destinatarios.*')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Asunto --}}
                            <div class="mb-3">
                                <label for="asunto" class="form-label fw-semibold">Asunto</label>
                                <input
                                    type="text"
                                    id="asunto"
                                    name="asunto"
                                    class="form-control"
                                    value="{{ old('asunto') }}"
                                    maxlength="120"
                                    placeholder="(opcional)"
                                >
                                @error('asunto')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Cuerpo --}}
                            <div class="mb-3">
                                <label for="cuerpo" class="form-label fw-semibold">
                                    Mensaje <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    id="cuerpo"
                                    name="cuerpo"
                                    rows="8"
                                    class="form-control"
                                    maxlength="5000"
                                    required
                                >{{ old('cuerpo') }}</textarea>
                                @error('cuerpo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                                <a href="{{ route('mensajes.index') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
