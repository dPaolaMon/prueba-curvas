@php
	$editando = isset($usuario) && $usuario->id;
@endphp

@csrf
@if($editando)
	@method('PUT')
@endif

@include('_partials.swal-form-errors')

<div class="row g-3">
	<div class="col-12 col-md-6">
		<label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
		<input
			type="text"
			id="name"
			name="name"
			class="form-control"
			value="{{ old('name', $usuario->name ?? '') }}"
			required
		>
	</div>

	<div class="col-12 col-md-6">
		<label for="username" class="form-label">Username <span class="text-danger">*</span></label>
		<input
			type="text"
			id="username"
			name="username"
			class="form-control"
			value="{{ old('username', $usuario->username ?? '') }}"
			required
		>
	</div>

	<div class="col-12 col-md-6">
		<label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
		<input
			type="email"
			id="email"
			name="email"
			class="form-control"
			value="{{ old('email', $usuario->email ?? '') }}"
			required
		>
	</div>

	<div class="col-12 col-md-6">
		<label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
		<select id="role" name="role" class="form-select" required>
			<option value="">-- Seleccione un rol --</option>
			@foreach($roles as $rol)
				<option value="{{ $rol }}" {{ old('role', $usuario->role ?? '') === $rol ? 'selected' : '' }}>
					{{ $rol }}
				</option>
			@endforeach
		</select>
	</div>

	<div class="col-12 col-md-6">
		<label for="password" class="form-label">
			{{ $editando ? 'Nueva Contraseña (opcional)' : 'Contraseña' }}
			@unless($editando)<span class="text-danger">*</span>@endunless
		</label>
		<input
			type="password"
			id="password"
			name="password"
			class="form-control"
			@if(!$editando) required @endif
		>
	</div>

	<div class="col-12 col-md-6">
		<label for="password_confirmation" class="form-label">
			Confirmar Contraseña @unless($editando)<span class="text-danger">*</span>@endunless
		</label>
		<input
			type="password"
			id="password_confirmation"
			name="password_confirmation"
			class="form-control"
			@if(!$editando) required @endif
		>
	</div>

	<div class="col-12">
		<div class="form-check">
			<input
				class="form-check-input"
				type="checkbox"
				id="suspendido"
				name="suspendido"
				value="1"
				{{ old('suspendido', $usuario->suspendido ?? false) ? 'checked' : '' }}
			>
			<label class="form-check-label" for="suspendido">Suspender usuario</label>
		</div>
	</div>
</div>

<div class="d-flex gap-2 justify-content-end pt-4 border-top mt-4">
	<a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
		<i class="bi bi-x-circle me-2"></i>Cancelar
	</a>
	<button type="submit" class="btn btn-primary">
		<i class="bi bi-check-circle me-2"></i>{{ $editando ? 'Actualizar Usuario' : 'Crear Usuario' }}
	</button>
</div>
