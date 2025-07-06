@extends('layouts.app')


@section('styles')
    <link href="{{ asset('css/usuario.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <h2>Agregar Usuario</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            </div>
            <div class="mb-3">
                <label>Apellido</label>
                <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
            </div>
            <div class="mb-3">
                <label>email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Roles</label>
                <select name="roles[]" class="form-control" multiple required>
                    <option value="" disabled selected>-- Haz click aquí para seleccionar un rol (Ctrl para varios) --
                    </option>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">
                    Mantén presionada la tecla <b>Ctrl</b> (o <b>Cmd</b> en Mac) para seleccionar más de un rol.
                </small>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" class="form-check-input" id="activo" value="1"
                    {{ old('activo') ? 'checked' : '' }}>
                <label class="form-check-label" for="activo">Activo</label>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
