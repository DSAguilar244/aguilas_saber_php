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
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}"
                    required>
            </div>
            <div class="mb-3">
                <label>Apellido</label>
                <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $usuario->apellido) }}"
                    required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}"
                    required>
            </div>
            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control"
                    value="{{ old('telefono', $usuario->telefono) }}">
            </div>
            <div class="mb-3">
                <label>Nueva Contraseña (opcional)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label>Roles</label>
                <select name="roles[]" class="form-control" multiple required>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}" {{ $usuario->roles->contains($rol->id) ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" class="form-check-input" id="activo" value="1"
                    {{ $usuario->activo ? 'checked' : '' }}>
                <label class="form-check-label" for="activo">Activo</label>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
