@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h2>Editar Usuario</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('usuarios.update', $usuario) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                   value="{{ old('nombre', $usuario->nombre) }}"
                   required maxlength="50" placeholder="Ej: Andrea, Juan"
                   title="Máximo 50 caracteres">
            <div id="nombre-error" class="text-danger small mt-1" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label for="apellido">Apellido <span class="text-danger">*</span></label>
            <input type="text" name="apellido" id="apellido" class="form-control"
                   value="{{ old('apellido', $usuario->apellido) }}"
                   required maxlength="50" placeholder="Ej: García, López"
                   title="Máximo 50 caracteres">
        </div>

        <div class="mb-3">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email', $usuario->email) }}"
                   required maxlength="100" placeholder="ejemplo@correo.com"
                   title="Correo electrónico válido">
            <div id="email-error" class="text-danger small mt-1" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control"
                   value="{{ old('telefono', $usuario->telefono) }}"
                   maxlength="20" pattern="[0-9+\s\-()]*"
                   placeholder="Ej: +593 987654321"
                   title="Solo números y símbolos válidos">
        </div>

        <div class="mb-3">
            <label for="password">Nueva Contraseña (opcional)</label>
            <input type="password" name="password" id="password" class="form-control"
                   minlength="8" maxlength="30" autocomplete="new-password"
                   placeholder="Mínimo 8 caracteres si deseas cambiarla"
                   title="Solo si deseas actualizarla">
            <small class="form-text text-muted">
                Solo llena este campo si deseas cambiar la contraseña actual.
            </small>
        </div>

        {{-- 🔐 Rol único --}}
        @php
            $isAdmin = auth()->user()->roles->contains('name', 'admin');
        @endphp

        <div class="mb-3">
            <label for="rol">Rol <span class="text-danger">*</span></label>
            <select name="rol" id="rol" class="form-control" required {{ $isAdmin ? '' : 'disabled' }}>
                @foreach ($roles as $rol)
                <option value="{{ $rol->id }}" {{ $usuario->roles->pluck('id')->contains($rol->id) ? 'selected' : '' }}>
                    {{ $rol->name }}
                </option>
                @endforeach
            </select>
            @if(!$isAdmin)
                <input type="hidden" name="rol" value="{{ $usuario->roles->first()->id ?? '' }}">
            @endif
        </div>

        @php
            $isEditingSelf = auth()->user()->id === $usuario->id;
        @endphp

        @if($isAdmin || !$isEditingSelf)
        <div class="form-check mb-3">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" class="form-check-input" id="activo" value="1"
                   {{ old('activo', $usuario->activo) ? 'checked' : '' }}>
            <label class="form-check-label" for="activo">Activo</label>
        </div>
        @else
            <input type="hidden" name="activo" value="{{ $usuario->activo ? '1' : '0' }}">
        @endif

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rolInput = document.getElementById('rol');
    const submitBtn = document.querySelector('button[type="submit"]');
    const form = document.querySelector('form');
    const nombreOriginal = "{{ $usuario->nombre }}";
    const emailOriginal = "{{ $usuario->email }}";
    let timer = null;

    function validarFormulario() {
        const nombre = nombreInput.value.trim();
        const apellido = apellidoInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const rol = rolInput.value;

        const todosCompletos = nombre && apellido && email && rol && (!password || password.length >= 8);
        submitBtn.disabled = !todosCompletos;
    }

    function validarCampo(input, tipo, original) {
        const valor = input.value.trim();
        const errorDiv = document.getElementById(`${tipo}-error`);

        if (!valor || valor.toLowerCase() === original.toLowerCase()) {
            errorDiv.style.display = 'none';
            errorDiv.innerText = '';
            validarFormulario();
            return;
        }

        fetch(`/usuarios/validar-${tipo}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ [tipo]: valor })
        })
        .then(res => res.json())
        .then(data => {
            if (data.existe) {
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>⚠️ El ${tipo} ya está registrado.`;
                errorDiv.style.display = 'block';
                submitBtn.disabled = true;
            } else {
                errorDiv.innerText = '';
                errorDiv.style.display = 'none';
                validarFormulario();
            }
        });
    }

    nombreInput.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => validarCampo(nombreInput, 'nombre', nombreOriginal), 400);
    });

    emailInput.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => validarCampo(emailInput, 'email', emailOriginal), 400);
    });

    [apellidoInput, passwordInput, rolInput].forEach(input => {
        input.addEventListener('change', validarFormulario);
        input.addEventListener('input', validarFormulario);
    });

    form.addEventListener('submit', function (e) {
        const nombre = nombreInput.value.trim();
        const apellido = apellidoInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const rol = rolInput.value;

        if (!nombre) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Nombre'); nombreInput.focus(); return false; }
        if (!apellido) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Apellido'); apellidoInput.focus(); return false; }
        if (!email) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Email'); emailInput.focus(); return false; }
        if (password && password.length < 8) { e.preventDefault(); alert('⚠️ La contraseña debe tener al menos 8 caracteres'); passwordInput.focus(); return false; }
        if (!rol) { e.preventDefault(); alert('⚠️ Por favor, selecciona un Rol'); rolInput.focus(); return false; }
    });

    validarFormulario();
});
</script>
@endsection